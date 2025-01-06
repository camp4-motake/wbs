import { $ } from 'zx';

const packageJson = await import( '../package.json', {
	with: { type: 'json' },
} );
const { workspaces } = packageJson.default;

process.env.FORCE_COLOR = '1';
$.verbose = true;

// dev task
await Promise.all( [
	$`npx browser-sync start --config bs-config.js`,
	...workspaces.map(
		async ( ws ) => await $`npm run dev --if-present -w ${ ws }`
	),
] );
