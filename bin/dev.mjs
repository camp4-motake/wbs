#!/usr/bin/env zx

import { $ } from 'zx';
import { workspaces } from './util.mjs';

process.env.FORCE_COLOR = '1';
$.verbose = true;

await Promise.all( [
	$`node ./bin/vscode-helper.mjs --watch`,
	$`npx browser-sync start --config bs-config.js`,
	...workspaces.map( ( ws ) => $`npm run dev --if-present -w ${ ws }` ),
] );
