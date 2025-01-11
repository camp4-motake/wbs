/* eslint-disable no-console */
import chokidar from 'chokidar';
import { $ } from 'zx';

const outFile = './env/.vscode-helper/_theme-json-styles.css';

process.env.FORCE_COLOR = '1';
$.verbose = true;

const generateThemeJsonVariables = async () => {
	try {
		await $`wp-env run cli wp export-theme-json-styles -- --output=${ outFile }`;
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
