<?php

/**
 * Theme view Assets
 *
 * @package wbs
 */

namespace Site\Theme\Assets;

/**
 * Read a built asset manifest (index.asset.php).
 *
 * Uses include (not include_once) so the manifest array is returned on every
 * call: the same asset is read more than once per request (enqueue_assets runs
 * on both wp_enqueue_scripts and enqueue_block_assets, and block view assets are
 * already require()d by core during registration), and include_once would return
 * true instead of the array on those repeat reads.
 *
 * @param string $path Absolute path to the index.asset.php file.
 * @return array Manifest with 'dependencies' (array) and 'version' (string|null).
 */
function read_asset_manifest( $path ) {
	$ctx = include $path;

	return array(
		'dependencies' => ( ! empty( $ctx['dependencies'] ) ) ? $ctx['dependencies'] : array(),
		'version'      => ( ! empty( $ctx['version'] ) ) ? $ctx['version'] : null,
	);
}

/**
 * assets
 */
function enqueue_assets() {
	$is_admin = is_admin(); // "true" is admin only
	$prefix   = wp_get_theme()->get( 'TextDomain' );

	// assets
	$assets = array(
		'block-styles'     => array(
			'path'   => 'build/block-styles/register-styles',
			'script' => $is_admin,
			'style'  => false,
		),
		'block-variations' => array(
			'path'   => 'build/block-variations',
			'script' => $is_admin,
			'style'  => false, // Enable when using variations
		),
		'formats'          => array(
			'path'   => 'build/formats',
			'script' => $is_admin,
			'style'  => true,
		),
		'main'             => array(
			'path'   => 'build/main',
			'script' => ! $is_admin,
			'style'  => ! $is_admin,
		),
		'editor'           => array(
			'path'   => 'build/editor',
			'script' => $is_admin,
			'style'  => $is_admin,
		),
	);

	foreach ( $assets as $key => $asset ) {
		$path = get_theme_file_path( $asset['path'] . '/index.asset.php' );

		if ( ! file_exists( $path ) ) {
			continue;
		}

		$manifest     = read_asset_manifest( $path );
		$dependencies = $manifest['dependencies'];
		$version      = $manifest['version'];

		if ( $asset['style'] && file_exists( get_theme_file_path( $asset['path'] . '/index.css' ) ) ) {
			$deps = $is_admin ? array() : array( 'global-styles' );
			wp_enqueue_style(
				"$prefix-theme-$key-styles",
				get_theme_file_uri( $asset['path'] . '/index.css' ),
				$deps,
				$version,
			);
		}

		if ( $asset['script'] && file_exists( get_theme_file_path( $asset['path'] . '/index.js' ) ) ) {
			if ( ! empty( $asset['module'] ) ) {
				wp_enqueue_script_module(
					"$prefix-theme-$key-scripts",
					get_theme_file_uri( $asset['path'] . '/index.js' ),
					$dependencies,
					$version
				);
			} else {
				wp_enqueue_script(
					"$prefix-theme-$key-scripts",
					get_theme_file_uri( $asset['path'] . '/index.js' ),
					$dependencies,
					$version,
					false
				);
			}
		}
	}
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_assets', 200 );
add_action( 'enqueue_block_assets', __NAMESPACE__ . '\\enqueue_assets', 200 );


/**
 * Conditionally enqueue per-block CSS built under a directory.
 *
 * Each map entry pairs a build subdirectory name with the block type it styles;
 * the CSS is attached via wp_enqueue_block_style() so it loads only when that
 * block is rendered.
 *
 * @param string $build_base Build directory holding the per-block subdirs (e.g. 'build/block-styles').
 * @param array  $block_map  Map of subdirectory name => block type.
 * @return void
 */
function enqueue_conditional_block_styles( $build_base, $block_map ) {

	$prefix = wp_get_theme()->get( 'TextDomain' );
	$suffix = basename( $build_base );

	foreach ( $block_map as $name => $block_type ) {
		$dir      = $build_base . '/' . $name;
		$path     = get_theme_file_path( $dir . '/index.asset.php' );
		$css_path = get_theme_file_path( $dir . '/index.css' );

		if ( ! file_exists( $path ) || ! file_exists( $css_path ) ) {
			continue;
		}

		$manifest = read_asset_manifest( $path );

		wp_enqueue_block_style(
			$block_type,
			array(
				'handle' => "$prefix-theme-$suffix-$name",
				'src'    => get_theme_file_uri( $dir . '/index.css' ),
				'path'   => $css_path,
				'deps'   => $manifest['dependencies'],
				'ver'    => $manifest['version'],
			)
		);
	}
}

/**
 * Enqueue block styles
 *
 * @return void
 */
function enqueue_block_styles() {

	// target styled core blocks: build subdir => block type.
	$styled_blocks = array(
		'accordion'  => 'core/accordion',
		'buttons'    => 'core/buttons',
		'image'      => 'core/image',
		'media-text' => 'core/media-text',
	);

	enqueue_conditional_block_styles( 'build/block-styles', $styled_blocks );
}
add_action( 'init', __NAMESPACE__ . '\\enqueue_block_styles', 200 );

/**
 * Enqueue block variation styles
 *
 * Variation front-end CSS under build/block-variations/<name>/ is attached to its
 * wrapper block so it loads only when that block is rendered, mirroring
 * enqueue_block_styles().
 *
 * @return void
 */
function enqueue_block_variation_styles() {

	// variation build subdir => wrapper block type.
	$variation_blocks = array();

	enqueue_conditional_block_styles( 'build/block-variations', $variation_blocks );
}
add_action( 'init', __NAMESPACE__ . '\\enqueue_block_variation_styles', 200 );
