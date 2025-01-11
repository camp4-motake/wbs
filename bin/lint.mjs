/* eslint-disable no-console */
import { $ } from 'zx';
import { workspaces } from './util.mjs';

const init = async () => {
	$.verbose = true;
	process.env.FORCE_COLOR = '1';

	if ( process.argv.includes( '--format' ) ) {
		/** format */
		await $`npx wp-scripts format . '!source'`;
		await $`npm run format --workspaces --if-present`;
		for ( const ws of workspaces ) {
			await $`docker run --rm --volume $PWD:/app composer composer format ${ ws }`;
		}
	} else {
		/** lint */
		await $`npm run lint --workspaces --if-present`;
		for ( const ws of workspaces ) {
			await $`docker run --rm --volume $PWD:/app composer composer lint ${ ws }`;
		}
	}
};

init().catch( ( error ) =>
	console.error( error?._stderr || error?.stderr || error )
);
