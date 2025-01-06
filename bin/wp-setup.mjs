import { $ } from 'zx';

process.env.FORCE_COLOR = '1';
$.verbose = true;

await $`docker run --rm --volume $PWD:/app composer composer install`;
await $`npx wp-env start`;
await $`npx wp-env run cli ./env/setup.sh`;
await $`npm run build`;
