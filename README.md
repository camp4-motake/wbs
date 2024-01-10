# WBS

WordPress Block Theme Site Starter

## prerequisites

- Docker Client
- Node.js ^18 || ^20

## setup environment

1, install dependencies

```sh
npm ci
```

2, Add ACF Pro key to `username` key in [auth.json](https://www.advancedcustomfields.com/resources/installing-acf-pro-with-composer/)

3, run wp auto setup

```sh
npm run setup
```

> If you want to customize wp-env configuration, add settings to `.wp-env.override.json` in advance (e.g. [change port numbers](https://github.com/WordPress/gutenberg/tree/HEAD/packages/env#custom-port-numbers), [etc](https://github.com/WordPress/gutenberg/tree/HEAD/packages/env#examples))

## tasks

 start wp-env

```sh
npx wp-env start
```

 or

```sh
npm start
```

dev

```sh
npm run dev
```

build

```sh
npm run build
```

lint

```sh
npm run lint
```

format

```sh
npm run format
```

### resources

- [wp-env](https://github.com/WordPress/gutenberg/tree/HEAD/packages/env#readme) | [wp-scripts](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/)
- [WordPress Developer Resources](https://developer.wordpress.org/)
- [npm workspaces](https://docs.npmjs.com/cli/v10/using-npm/workspaces)
