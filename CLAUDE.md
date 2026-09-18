# CLAUDE.md

## Overview

Starter/boilerplate for WordPress **block theme** sites by CAMP4 inc. The theme lives at `themes/wbs/` (prefix / text-domain `wbs`). The repo root is an npm-workspaces monorepo (`workspaces: ["themes/*"]`) that orchestrates local WordPress (`@wordpress/env` / Docker), builds (`@wordpress/scripts` + webpack, plus a post-build image step), and PHP/JS/CSS linting. Site content/admin strings are in Japanese; the WordPress core is the Japanese build.

This repo is **cloned per project and renamed** — a new site replaces `wbs` (directory name, text domain, block/pattern namespace, `WP_SITE_PREFIX` in `bin/deploy.sh`, `WP_THEME_NAME` in `env/setup.sh`, `@wbs/slider` in `tsconfig.json`). Keep additions here generic: anything site-specific belongs in the derived project, not in the boilerplate.

**Implementation targets WordPress 7.0+** — write code against WordPress 7.0 or later; do not add fallbacks or workarounds for older cores.

Language-specific coding rules (TS, CSS, PHP) live in `.claude/rules/*.md` and auto-load per file path — they are not repeated here.

## Answer-Only Requests

When the request is a question — "is it safe to delete this?", "where does this warning come from?", "why does X happen?" — reply with the answer and the evidence behind it. Do not write or edit files, enter plan mode, or start implementing. Describe the fix in prose and wait for an explicit go-ahead.

Investigate with read-only subagents (`Explore`, or `general-purpose` briefed as read-only) instead of editing to find out — they survey several places at once and cannot touch the tree. Anything described as a read-only command stays read-only, reporting only.

## Commands

Run from the repo root unless noted:

```sh
npm run setup        # one-time: composer install + wp-env start + env/setup.sh + build
npm run wp-env start # start local WordPress (Docker)
npm run dev          # 4 watchers in parallel: theme.json→CSS vars, browser-sync, images, wp-scripts (see bin/dev.mjs)
npm run check-types  # tsc -p themes/wbs --noEmit (always use --noEmit; bare tsc emits build-types/)
npm run cli -- <cmd> # run a command in the wp-env CLI container, e.g. npm run cli -- wp plugin list
npm run wp-flush     # flush rewrite rules (hard)
npm run plugin-zip   # generate distributable theme zip
```

- **PHP-only lint/format:** `npm run composer lint` / `npm run composer format` (phpcs/phpcbf via a `composer` Docker image; rules in `phpcs.xml.dist` → WordPress-Extra + PHPCompatibilityWP + VariableAnalysis, PHP 8.3+, WP 7.0+).
- **Bundled plugins** come from Composer (`repo.wp-packages.org` + `composer/installers`) and land in `plugins/`, which `.wp-env.json` maps to `wp-content/plugins`. Add a plugin by requiring `wp-plugin/<slug>`, not by listing a zip URL in `.wp-env.json`.
- **DB/uploads snapshot:** `npm run wp-export` / `npm run wp-import` (round-trips `env/_bkp/`).
- **Deploy:** `bin/deploy.sh {production|test|zip}` — builds then rsyncs `themes/wbs/` over SSH (paths from `.env`).
- There is **no automated test suite**.

## Theme architecture (`themes/wbs/`)

**PHP** — `functions.php` is a manifest of `require_once` includes; all logic lives under `inc/`, as flat files plus grouped subdirectories, with namespaces nested to match the path (`inc/helper/image.php` → `Site\Theme\Helper\Image`), using the namespaced-function-with-`add_action`/`add_filter` pattern. `inc/constants.php` defines breakpoint/orientation media-query constants (`MQ_SM`…`MQ_2XL`) and feature flags — use those constants rather than hardcoding values.

**Block templates / parts / patterns** — FSE-style: `templates/*.html`, `parts/*.html`, plus PHP-rendered `partials/*.php` and dynamic `patterns/*.php`. Core block patterns and the remote block directory are deliberately disabled (`inc/editor.php`, `inc/register-blocks.php`); custom block + pattern categories `wbs-theme-blocks` / `wbs-theme-patterns` are registered instead.

**Build pipeline (`src/` → `build/`)** — two stages, both wired in `themes/wbs/package.json`:

1. **webpack** via `@wordpress/scripts` with `--experimental-modules --webpack-copy-php` — no custom webpack config; PostCSS is configured in `themes/wbs/postcss.config.js` (note the convention that assets under `_inline/*` are inlined as data URIs). Entry points are derived from the `block.json` files under `src/` — every entry directory has one, including pseudo-entries like `src/main/block.json` (`{"script": "file:./index.js"}`); wp-scripts resolves those `file:./index.js` references by basename against `.ts`/`.tsx` sources, so block.json never needs to change for TS sources.
2. **`themes/wbs/bin/build-images.mjs`** — asset step outside the bundler: copies `src/images` → `build/images` (skipping `_*` dirs) and optimizes SVG with SVGO. In production it also emits AVIF variants and rewrites `url()` into `image-set()` in the CSS webpack has already emitted, so `build` must run it *after* webpack; dev runs it as a parallel watcher. preset-env's `image-set-function` is disabled so raw `url()`s survive for this step.

- `src/blocks/*` — custom blocks (each has a `block.json`, plus `edit`/`save`/`render.php`/`view` as needed). At runtime `inc/register-blocks.php` auto-registers everything under `build/blocks/*` via `glob` — a new block needs only its `src/blocks/<name>/block.json`, no wiring elsewhere.
- `src/shared` (hooks, components) is a library imported by entries, not an entry itself — it has no `block.json`.
- `src/slider` builds `@wbs/slider`, a shared WP Script Module (embla) registered in `inc/script-modules.php` and mapped to `src/slider/index.ts` by `tsconfig.json`. Consumers lazy-import it with `webpackIgnore` so it stays out of their bundles, and must be added to the `$consumers` map in `inc/script-modules.php` — otherwise the specifier never reaches the import map and fails to resolve at runtime. The map ships empty; add an entry when a slider block is introduced.
- `inc/assets.php` enqueues bundles by reading each `build/<name>/index.asset.php` (deps + version hash) and conditionally loads script/style based on `is_admin()`. Per-block CSS is attached via `wp_enqueue_block_style` from two maps — `$styled_blocks` (`build/block-styles`) and `$variation_blocks` (`build/block-variations`) — so a new per-block stylesheet must be registered in the matching map or it never loads.

## Conventions

- Commit messages: write in **English** using the [Conventional Commits](https://www.conventionalcommits.org/) format — `type(scope): subject` (imperative mood, lowercase subject). Common types: `feat`, `fix`, `refactor`, `build`, `docs`, `chore`, `style`, `test`, `perf`. Example: `feat(block): add post-card-link block`.
- TypeScript: the workspace `tsconfig.json` extends root `tsconfig.base.json` (strict, `moduleResolution: bundler`, isolated modules). Type-only imports must use `import type` — required by isolated modules, and it also keeps a `@wordpress/*` package out of the entry's `.asset.php` deps (a plain import adds it as a runtime dependency). `@wordpress/server-side-render` is shimmed in `src/type.d.ts`. `themes/wbs/src` is still a mix of `.js` and `.ts`/`.tsx`; write new code in TypeScript.
