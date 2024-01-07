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

2 Add ACF Pro key to username key in [auth.json](https://www.advancedcustomfields.com/resources/installing-acf-pro-with-composer/)

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

> Add to .wp-env.override.json if you want to [change port numbers](https://github.com/WordPress/gutenberg/tree/HEAD/packages/env#custom-port-numbers), [etc](https://github.com/WordPress/gutenberg/tree/HEAD/packages/env#examples).

3 run wp auto setup

```sh
npm run setup
```

## tasks

```sh
# start wp-env (or "npm start")
npx wp-env start

# dev (all workspace)
npm run dev

# build (all workspace)
npm run build

# lint
npm run lint

# format
npm run format
```

### resource

- [wp-env](https://github.com/WordPress/gutenberg/tree/HEAD/packages/env#readme) | [wp-scripts](https://developer.wordpress.org/block-editor/reference-guides/packages/packages-scripts/)
- [WordPress Developer Resources](https://developer.wordpress.org/)
