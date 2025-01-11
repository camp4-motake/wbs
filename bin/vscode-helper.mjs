/* eslint-disable no-console */
import chokidar from 'chokidar';
import { $ } from 'zx';

const outFile = './env/.vscode-helper/theme-json-variables.css';

const generateThemeJsonVariables = async () => {
	try {
		await $`wp-env run cli wp css-vars-to-vscode -- --format=css --output=${ outFile }`;
	} catch ( error ) {
		console.error( 'Error running wp-env:', error?.stderr ?? error );
	}
};

const init = async () => {
	generateThemeJsonVariables();

	if ( ! process.argv.includes( '--watch' ) ) {
		return;
	}

	const watcher = chokidar.watch( 'source/wp-content', {
		ignored: ( path, stats ) => {
			return !! ( stats?.isFile() && ! path.endsWith( `/theme.json` ) );
		},
	} );

	watcher.on( 'change', async ( path ) => {
		console.log( `File ${ path } has been changed` );
		generateThemeJsonVariables();
	} );

	console.log( `Watching for theme.json changes...` );
};

init().catch( console.error );
