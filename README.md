# WBS

WordPress Block Theme Site Starter

## prerequisites

- Docker Client
- Node.js ^18 || ^20

## setup

1 install deps

```sh
npm ci
```

2 Add ACF Pro key to `username` key in [auth.json](https://www.advancedcustomfields.com/resources/installing-acf-pro-with-composer/)

```json
{
 "http-basic": {
  "connect.advancedcustomfields.com": {
+   "username": "ACF_PRO_KEY",
   "password": "https://camp4.jp/"
  }
 }
}
```

3 run wp auto setup

```sh
npm run setup
```

> If you want to customize wp-env configuration, add settings to `.wp-env.override.json` in advance (e.g. [change port numbers](https://github.com/WordPress/gutenberg/tree/HEAD/packages/env#custom-port-numbers), [etc](https://github.com/WordPress/gutenberg/tree/HEAD/packages/env#examples))

## tasks

```sh
# start wp-env (or "npm start")
npx wp-env start

# dev
npm run dev

# build
npm run build

# lint
npm run lint

# format
npm run format
```

### resources

- [wp-env](https://github.com/WordPress/gutenberg/tree/HEAD/packages/env#readme) | [wp-scripts](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/)
- [WordPress Developer Resources](https://developer.wordpress.org/)
- [npm workspaces](https://docs.npmjs.com/cli/v10/using-npm/workspaces)
