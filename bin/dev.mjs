import { $ } from 'zx';

const packageJson = await import( '../package.json', {
	with: { type: 'json' },
} );
const { workspaces } = packageJson.default;

process.env.FORCE_COLOR = '1';
$.verbose = true;

// dev task
await Promise.all( [
	$`node ./bin/vscode-helper.mjs`,
	$`npx browser-sync start --config bs-config.js`,
	...workspaces.map( ( ws ) => $`npm run dev --if-present -w ${ ws }` ),
] );
