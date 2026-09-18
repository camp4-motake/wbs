# WBS

WordPress block theme starter. The theme lives in `themes/wbs/`.

## prerequisites

- Docker Client
- Node.js >=24 (see `.nvmrc`)

Targets WordPress >=7.1.1 / PHP >=8.4. The local environment (`.wp-env.json`) runs the
Japanese WordPress build on PHP 8.4.

## setup environment

1. install dependencies

```sh
npm ci
```

2. run auto WordPress setup

`npm run setup` installs the Composer-managed plugins into `plugins/`, starts wp-env,
applies `env/setup.sh` and builds the theme.

```sh
npm run setup
```

## tasks

```sh
# start wp-env
npm run wp-env start

# dev (theme.json watcher + browser-sync + images + wp-scripts)
npm run dev

# build
npm run build

# build images only (SVGO + AVIF)
npm run build:images -w wbs-theme

# lint (js / css / php)
npm run lint

# format (js / css / php)
npm run format

# type check
npm run check-types

# run a command in the wp-env CLI container
npm run cli -- wp plugin list

# flush rewrite rules
npm run wp-flush

# snapshot / restore the DB and uploads (env/_bkp/)
npm run wp-export
npm run wp-import

# generate plugin/theme zip
npm run plugin-zip

# deploy (production | test | zip)
./bin/deploy.sh production
```

## bundled plugins

WordPress plugins are managed with Composer via `repo.wp-packages.org` and installed
into `plugins/` (gitignored) by `composer/installers`. Add one by requiring
`wp-plugin/<slug>` in `composer.json` — not by listing a zip URL in `.wp-env.json`.

```sh
npm run composer require --dev wp-plugin/<slug>
```

## resources

- [WordPress Developer Resources](https://developer.wordpress.org/)
- [wp-env](https://github.com/WordPress/gutenberg/tree/HEAD/packages/env#readme)
- [wp-scripts](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/)
- [npm workspaces](https://docs.npmjs.com/cli/v10/using-npm/workspaces)
