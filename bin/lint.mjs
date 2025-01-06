import { $ } from 'zx';

const packageJson = await import( '../package.json', {
	with: { type: 'json' },
} );
const { workspaces } = packageJson.default;

const task = {
	format: async () => {
		await $`npx wp-scripts format . '!source'`;
		await $`npm run format --workspaces --if-present`;
		workspaces.map(
			async ( ws ) =>
				await $`docker run --rm --volume $PWD:/app composer composer format ${ ws }`
		);
	},

	lint: async () => {
		await $`npm run lint --workspaces --if-present`;
		workspaces.map(
			async ( ws ) =>
				await $`docker run --rm --volume $PWD:/app composer composer lint ${ ws }`
		);
	},
};

const run = () => {
	$.verbose = true;
	process.env.FORCE_COLOR = '1';

	if ( process.argv.includes( '--format' ) ) {
		task.format();
	} else {
		task.lint();
	}
};

run();
