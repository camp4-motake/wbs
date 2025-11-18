#!/usr/bin/env zx

import { $ } from 'zx';

$.verbose = true;
process.env.FORCE_COLOR = '1';

// format
if ( process.argv.includes( '--format' ) ) {
	await $`wp-scripts lint-style --fix`;
	await $`wp-scripts lint-js --fix`;
	await $`wp-scripts format`;
	await $`docker run --rm --volume $PWD:/app composer composer format`;
}

// lint
else {
	await $`wp-scripts lint-style`;
	await $`wp-scripts lint-js`;
	await $`docker run --rm --volume $PWD:/app composer composer lint`;
}
