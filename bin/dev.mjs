import { $ } from 'zx';

process.env.FORCE_COLOR = '1';
$.verbose = true;

// dev task
await Promise.all( [
	$`browser-sync start --config bs-config.js`,
	$`pnpm -r run dev`,
] );
