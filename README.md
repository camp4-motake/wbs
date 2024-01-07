# WBS

WordPress Block Theme Site Starter

## setup

1, install deps

```sh
npm ci
```

2, Add ACF Pro key to username key in [auth.json](https://www.advancedcustomfields.com/resources/installing-acf-pro-with-composer/)

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

3, run wp auto setup

```sh
npm run setup
```

## task

```sh
# start wp-env
npm start
# or
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

[WordPress Developer Resources](https://developer.wordpress.org/)
