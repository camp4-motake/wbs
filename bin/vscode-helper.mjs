/* eslint-disable no-console */
import chokidar from 'chokidar';
import { $ } from 'zx';

const target = 'theme.json';
process.env.FORCE_COLOR = '1';
$.verbose = true;

const generateHelperFile = async () => {
	try {
		await $`wp-env run cli wp css-vars-to-vscode -- --format=css --output=./env/.vscode-helper/theme-json-variables.css`;
	} catch ( error ) {
		console.error( 'Error running wp-env:', error?.stderr ?? error );
	}
};

const init = async () => {
	generateHelperFile();

	if ( process.argv.includes( '--watch' ) ) {
		const watcher = chokidar.watch( 'source/wp-content', {
			ignored: ( path, stats ) => {
				return !! ( stats?.isFile() && ! path.endsWith( target ) );
			},
		} );

		watcher.on( 'change', async ( path ) => {
			console.log( `File ${ path } has been changed` );
			generateHelperFile();
		} );

		console.log( `Watching for ${ target } changes...` );
	}
};

init().catch( console.error );
