/* eslint-disable no-console */
import chokidar from 'chokidar';
import { $ } from 'zx';

process.env.FORCE_COLOR = '1';
$.verbose = true;

const target = 'theme.json';
const generateHelper = async () =>
	await $`wp-env run cli wp css-vars-to-vscode -- --format=css --output=./env/.vscode-helper/theme-json-variables.css`;

const init = async () => {
	const watcher = chokidar.watch( 'source/wp-content', {
		ignored: ( path, stats ) => {
			return !! ( stats?.isFile() && ! path.endsWith( target ) );
		},
	} );

	watcher.on( 'change', async ( path ) => {
		console.log( `File ${ path } has been changed` );
		generateHelper();
	} );

	generateHelper();
	console.log( `Watching for ${ target } changes...` );
};

init().catch( console.error );
