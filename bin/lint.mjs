/* eslint-disable no-console */
import { findWorkspaces } from 'find-workspaces';
import { relative } from 'node:path';
import { $ } from 'zx';

const workspaces =
	findWorkspaces()?.map( ( ws ) =>
		relative( process.cwd(), ws?.location )
	) || [];

const init = async () => {
	$.verbose = true;
	process.env.FORCE_COLOR = '1';

	/**
	 * format
	 */
	if ( process.argv.includes( '--format' ) ) {
		await $`npx wp-scripts format . '!source'`;
		await $`npm run format --workspaces --if-present`;
		for ( const ws of workspaces ) {
			await $`docker run --rm --volume $PWD:/app composer composer format ${ ws }`;
		}
		return;
	}

	/**
	 * lint
	 */
	await $`npm run lint --workspaces --if-present`;
	for ( const ws of workspaces ) {
		await $`docker run --rm --volume $PWD:/app composer composer lint ${ ws }`;
	}
};

init().catch( ( error ) =>
	console.error( error?._stderr || error?.stderr || error )
);
