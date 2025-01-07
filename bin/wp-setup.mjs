import { $ } from 'zx';

process.env.FORCE_COLOR = '1';
$.verbose = true;

await $`npx wp-env start`;
await $`npx wp-env run cli ./env/setup.sh`;
await $`npm run build`;
