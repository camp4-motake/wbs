#!/usr/bin/env node
/* eslint-disable no-console */

/**
 * Bundler-agnostic asset pipeline for theme `build/` output.
 *
 * Replaces what used to live inside the webpack factory:
 *   - copy `src/images` → `build/images` (excluding `_*` dirs)
 *   - optimize SVGs with SVGO
 *   - generate AVIF variants (production only)
 *   - rewrite `url(...png|jpg)` in built CSS to `image-set()` with `?v=hash`
 *
 * It runs as a postbuild step (or `--watch` alongside the bundler in dev), so it
 * survives a future move off webpack (e.g. esbuild / `@wordpress/build`).
 *
 * The production run must come *after* the bundler: it rewrites already-emitted
 * CSS, and the images it converts include the content-hashed copies webpack
 * emits for every `url()` it resolves out of `src/`.
 *
 * Usage:
 *   node bin/build-images.mjs [themeDir] [--production] [--watch]
 *
 * `themeDir` is resolved relative to CWD (`.` when run from a theme package).
 */

import { createHash } from 'node:crypto';
import { existsSync } from 'node:fs';
import { cp, mkdir, readdir, readFile, writeFile } from 'node:fs/promises';
import path from 'node:path';

import postcss from 'postcss';
import { optimize as svgoOptimize } from 'svgo';

const QUALITY = 85;
const IMG_RE = /\.(png|jpe?g)$/i;

const TARGET_PROPS = new Set( [
	'background',
	'background-image',
	'border-image',
	'border-image-source',
	'list-style-image',
	'mask-image',
] );

const MIME = { png: 'image/png', jpg: 'image/jpeg', jpeg: 'image/jpeg' };

const args = process.argv.slice( 2 );
const flags = new Set( args.filter( ( a ) => a.startsWith( '--' ) ) );
const positional = args.filter( ( a ) => ! a.startsWith( '--' ) );

const themeDir = path.resolve( process.cwd(), positional[ 0 ] ?? '.' );
const watch = flags.has( '--watch' );
const prod =
	flags.has( '--production' ) ||
	flags.has( '--prod' ) ||
	process.env.NODE_ENV === 'production';

const srcImages = path.join( themeDir, 'src', 'images' );
const buildDir = path.join( themeDir, 'build' );
const buildImages = path.join( buildDir, 'images' );

/**
 * Recursively list every file under `dir` (absolute paths). Missing dir → `[]`.
 * @param {string} dir
 * @return {Promise<string[]>} Absolute paths of every file found.
 */
async function listFiles( dir ) {
	let entries;
	try {
		entries = await readdir( dir, { withFileTypes: true } );
	} catch {
		return [];
	}
	const out = [];
	for ( const entry of entries ) {
		const full = path.join( dir, entry.name );
		if ( entry.isDirectory() ) {
			out.push( ...( await listFiles( full ) ) );
		} else if ( entry.isFile() ) {
			out.push( full );
		}
	}
	return out;
}

/**
 * Content hash (8 hex chars) used for `?v=` cache busting.
 * @param {Buffer} buf
 * @return {string} The 8-char hex hash.
 */
function contentHash( buf ) {
	return createHash( 'sha256' ).update( buf ).digest( 'hex' ).slice( 0, 8 );
}

/**
 * Copy `src/images` → `build/images`, skipping files inside `_*` directories
 * (matches the old CopyPlugin `ignore: ['**\/_*\/**']`). No-op if no images.
 */
async function copyImages() {
	if ( ! existsSync( srcImages ) ) {
		return;
	}

	const copies = ( await listFiles( srcImages ) )
		.map( ( file ) => ( {
			file,
			rel: path.relative( srcImages, file ),
		} ) )
		.filter(
			( { rel } ) =>
				! path
					.dirname( rel )
					.split( path.sep )
					.some( ( s ) => s.startsWith( '_' ) )
		)
		.map( ( { file, rel } ) => ( {
			file,
			dest: path.join( buildImages, rel ),
		} ) );

	// Create each needed directory once, then copy all files concurrently.
	const dirs = new Set( copies.map( ( { dest } ) => path.dirname( dest ) ) );
	await Promise.all(
		[ ...dirs ].map( ( dir ) => mkdir( dir, { recursive: true } ) )
	);
	await Promise.all( copies.map( ( { file, dest } ) => cp( file, dest ) ) );
}

/**
 * Minify the given SVG files in place.
 * @param {string[]} svgFiles
 */
async function optimizeSvg( svgFiles ) {
	await Promise.all(
		svgFiles.map( async ( file ) => {
			const { data } = svgoOptimize( await readFile( file, 'utf8' ), {
				// `path` lets `prefixIds` derive a per-file prefix from the
				// basename; without it every file falls back to the same
				// default prefix and IDs still collide when SVGs are inlined.
				path: file,
				multipass: true,
				plugins: [ 'preset-default', 'prefixIds' ],
			} );
			await writeFile( file, data );
		} )
	);
}

/**
 * Generate an AVIF variant for each PNG/JPG and return a map of `build/`-relative
 * image path → cache-bust hashes. Source and AVIF buffers are hashed in the same
 * pass that produces them, so nothing is read from disk twice.
 * @param {string[]} imgFiles
 * @return {Promise<Map<string, {hash: string, avifHash: string}>>} Hash map keyed by image path.
 */
async function generateAvifAndHash( imgFiles ) {
	const map = new Map();
	if ( ! imgFiles.length ) {
		return map;
	}
	// Lazy import: `sharp` is a native module only needed for production AVIF,
	// so dev never loads it.
	const { default: sharp } = await import( 'sharp' );
	await Promise.all(
		imgFiles.map( async ( file ) => {
			const src = await readFile( file );
			const avifBuf = await sharp( src )
				.avif( { quality: QUALITY } )
				.toBuffer();
			await writeFile( file.replace( /\.\w+$/, '.avif' ), avifBuf );

			const rel = path
				.relative( buildDir, file )
				.split( path.sep )
				.join( '/' );
			map.set( rel, {
				hash: contentHash( src ),
				avifHash: contentHash( avifBuf ),
			} );
		} )
	);
	return map;
}

/**
 * Pure transform: rewrite `url(...png|jpg)` in a CSS string to `image-set()`
 * with AVIF first and a `?v=hash` cache-bust suffix. Ported verbatim from the
 * old webpack plugin so the cascade behaviour (custom-property direct replace
 * vs. double-declaration fallback for standard props) is preserved. Returns the
 * CSS unchanged when nothing matches.
 *
 * Runs as a post-build step on already-emitted CSS. `postcss.config.js` disables
 * preset-env's `image-set-function` so the source `url()`s reach the built CSS
 * intact for this step to wrap — keep the two in sync.
 * @param {string} css
 * @param {Map}    imgMap
 * @return {string} The rewritten CSS (unchanged if nothing matched).
 */
function rewriteCss( css, imgMap ) {
	if ( ! imgMap.size ) {
		return css;
	}
	const urlRe = /url\(\s*['"]?([^'")]+\.(png|jpe?g))['"]?\s*\)/gi;
	const assetKeys = [ ...imgMap.keys() ];

	const root = postcss.parse( css );
	let modified = false;

	root.walkDecls( ( decl ) => {
		const isCustomProp = decl.prop.startsWith( '--' );
		if ( ! TARGET_PROPS.has( decl.prop ) && ! isCustomProp ) {
			return;
		}
		if ( decl.value.includes( 'image-set(' ) ) {
			return;
		}

		const matches = [ ...decl.value.matchAll( urlRe ) ];
		if ( ! matches.length ) {
			return;
		}

		let origValue = decl.value;
		let newValue = decl.value;

		for ( const m of [ ...matches ].reverse() ) {
			const [ full, filePath, ext ] = m;

			const assetName = assetKeys.find(
				( a ) => filePath.endsWith( a ) || filePath.includes( a )
			);
			if ( ! assetName ) {
				continue;
			}

			const { hash: pngHash, avifHash } = imgMap.get( assetName );

			const pngUrl = pngHash ? `url(${ filePath }?v=${ pngHash })` : full;
			origValue =
				origValue.slice( 0, m.index ) +
				pngUrl +
				origValue.slice( m.index + full.length );

			// The AVIF sits next to its source, so swap the extension on the URL
			// exactly as authored: entry CSS lives in nested dirs
			// (`build/block-styles/*/index.css`) and reaches images through a
			// relative `../../images/…`, which a `build/`-relative path would break.
			const avifPath = filePath.replace( /\.\w+$/, '.avif' );
			const mime = MIME[ ext.toLowerCase() ];
			const avifUrlPart = avifHash
				? `url(${ avifPath }?v=${ avifHash })`
				: `url(${ avifPath })`;
			const pngUrlPart = pngHash
				? `url(${ filePath }?v=${ pngHash })`
				: `url(${ filePath })`;
			const imageSet = `image-set(${ avifUrlPart } type("image/avif"), ${ pngUrlPart } type("${ mime }"))`;

			newValue =
				newValue.slice( 0, m.index ) +
				imageSet +
				newValue.slice( m.index + full.length );
		}

		if ( newValue !== decl.value ) {
			if ( isCustomProp ) {
				// Custom properties accept any token list, so a second
				// declaration never "wins" by cascade — replace directly.
				decl.value = newValue;
			} else {
				decl.value = origValue;
				decl.cloneAfter( { value: newValue } );
			}
			modified = true;
		}
	} );

	return modified ? root.toResult().css : css;
}

/**
 * Apply {@link rewriteCss} to the given CSS files, writing back only changes.
 * @param {string[]} cssFiles
 * @param {Map}      imgMap
 */
async function rewriteImageSet( cssFiles, imgMap ) {
	if ( ! imgMap.size ) {
		return;
	}
	await Promise.all(
		cssFiles.map( async ( cssFile ) => {
			const css = await readFile( cssFile, 'utf8' );
			const out = rewriteCss( css, imgMap );
			if ( out !== css ) {
				await writeFile( cssFile, out );
			}
		} )
	);
}

/**
 * Run the full pipeline once.
 */
async function run() {
	await copyImages();

	// Walk the build tree once, then route files by kind.
	const files = await listFiles( buildDir );
	const inImages = ( f ) => f.startsWith( buildImages + path.sep );
	const svgs = files.filter( ( f ) => inImages( f ) && /\.svg$/i.test( f ) );

	// AVIF + CSS image-set are production-only; in dev the PHP layer falls back
	// to plain `url()` when no `.avif` sibling exists.
	if ( ! prod ) {
		await optimizeSvg( svgs );
		return;
	}

	const imgs = files.filter( ( f ) => inImages( f ) && IMG_RE.test( f ) );
	const cssFiles = files.filter(
		( f ) => /\.css$/i.test( f ) && ! /\.map$/i.test( f )
	);
	const [ , imgMap ] = await Promise.all( [
		optimizeSvg( svgs ),
		generateAvifAndHash( imgs ),
	] );
	await rewriteImageSet( cssFiles, imgMap );
}

async function init() {
	await run();

	if ( ! watch ) {
		return;
	}

	const { default: chokidar } = await import( 'chokidar' );

	// Watch source images (author edits) and the bundler's CSS output. Watching
	// the bundler output lets us re-assert images after the dev script wipes
	// `build/` (`rimraf build/*`) and webpack re-emits — the order between that
	// and our initial copy is otherwise racy. The `images/` output dir is
	// ignored so our own writes never retrigger the watcher.
	const watcher = chokidar.watch( [ srcImages, buildDir ], {
		ignoreInitial: true,
		ignored: ( p ) =>
			p === buildImages || p.startsWith( buildImages + path.sep ),
	} );

	let timer;
	const rebuild = ( file ) => {
		clearTimeout( timer );
		timer = setTimeout( () => {
			console.log( `[build-assets] ${ path.basename( file ) } changed` );
			run().catch( console.error );
		}, 150 );
	};
	watcher.on( 'add', rebuild );
	watcher.on( 'change', rebuild );
	watcher.on( 'unlink', rebuild );
	console.log( `[build-assets] watching ${ themeDir } …` );
}

init().catch( ( error ) => {
	console.error( error );
	process.exitCode = 1;
} );
