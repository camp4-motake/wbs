import { findWorkspaces as fws } from 'find-workspaces';
import { relative } from 'node:path';
import { $ } from 'zx';

const workspaces = fws()?.map( ( ws ) =>
	relative( process.cwd(), ws?.location )
);

process.env.FORCE_COLOR = '1';
$.verbose = true;

await Promise.all( [
	$`node ./bin/vscode-helper.mjs --watch`,
	$`npx browser-sync start --config bs-config.js`,
	...workspaces.map( ( ws ) => $`npm run dev --if-present -w ${ ws }` ),
] );
