# WBS

## prerequisites

- Docker Client
- Node.js >=20

## setup environment

1. install dependencies

```sh
npm ci
```

2. run auto WordPress setup

```sh
npm run setup
```

## tasks

```sh
# start wp-env
npm run wp-env start

# dev
npm run dev

# build
npm run build

# lint
npm run lint

# format
npm run format

# generate plugin/theme zip
npm run plugin-zip
```

## resources

- [WordPress Developer Resources](https://developer.wordpress.org/)
- [wp-env](https://github.com/WordPress/gutenberg/tree/HEAD/packages/env#readme)
- [wp-scripts](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/)
- [npm workspaces](https://docs.npmjs.com/cli/v10/using-npm/workspaces)
