import { $ } from 'zx';

const packageJson = await import( '../package.json', {
	with: { type: 'json' },
} );
const { workspaces } = packageJson.default;

$.verbose = true;
process.env.FORCE_COLOR = '1';

// format
if ( process.argv.includes( '--format' ) ) {
	await $`npx wp-scripts format . '!source'`;
	await $`npm run format --workspaces --if-present`;
	workspaces.forEach( async ( ws ) => {
		await $`docker run --rm --volume $PWD:/app composer composer format ${ ws }`;
	} );
}

// lint
else {
	await $`npm run lint --workspaces --if-present`;
	workspaces.forEach( async ( ws ) => {
		await $`docker run --rm --volume $PWD:/app composer composer lint ${ ws }`;
	} );
}
