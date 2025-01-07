import { $ } from 'zx';

process.env.FORCE_COLOR = '1';
$.verbose = true;

await $`pnpm run wp-env start`;
await $`pnpm run wp-env run cli ./env/setup.sh`;
await $`pnpm run build`;
