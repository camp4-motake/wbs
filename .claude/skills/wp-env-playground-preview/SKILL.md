---
name: wp-env-playground-preview
description: "Use to preview/verify theme or block visuals locally without Docker: spin up wp-env's Playground (WASM/SQLite) runtime inside a git worktree, then drive it with playwright-cli (screenshots + layout measurements). Use for block/template/part/pattern layout checks on a branch without touching main or the host wp-env."
compatibility: "Requires @wordpress/env >= 11.8 (npx-cached) and the playwright-cli skill. No Docker. Playground DB is ephemeral (volatile SQLite) and WP-CLI (`wp-env run`) is unsupported on it."
---

# WP-env Playground Preview

Verify how a block theme/blocks look on a branch, in isolation, with no Docker fight
(no proxy/policy issues). Pairs with the `playwright-cli` skill for the browser side.

## When to use

- Visual / layout verification of a block, template, part, or pattern.
- You are on a feature branch and must NOT disturb `main` or the host wp-env.
- A quick throwaway WP instance is enough — you can seed a few posts/pages yourself.

Do NOT use when you need production-like content (a DB/uploads restore) or WP-CLI:
those require the Docker runtime.

## Prerequisites

- `playwright-cli` installed (see the `playwright-cli` skill). In a sandbox, first run:
  `playwright-cli install` then `playwright-cli install-browser chromium`.
- `@wordpress/env` resolvable via `npx` (most theme repos expose it as a `wp-env` npm
  script = `npx @wordpress/env`).

## Procedure

### 1) Work in a git worktree

Keep `main` untouched. Either reuse an existing worktree (`git worktree list`) or:
`git worktree add ../<repo>-<topic> <branch>`. Anchor every file op to the worktree root.

### 2) Wire dependencies (all git-ignored, never commit)

From the worktree root:

- `ln -s ../<main-checkout>/node_modules node_modules` — share the main install instead
  of re-installing.
- If the project uses Composer and your build/lint needs it, copy `vendor` as a REAL
  directory (a symlink will not work — Docker/Playground mounts cannot follow an external
  symlink): `cp -R ../<main-checkout>/vendor vendor`. Optional for pure layout checks.
- Create any git-ignored host paths referenced by `.wp-env.json` mappings — Playground
  hard-fails if a mapped host path is missing. Tracked mapping dirs already exist in a
  fresh worktree; only git-ignored targets (e.g. a manually-populated `plugins/` dir)
  need `mkdir -p`. An empty dir is fine for layout checks.

### 3) Isolate the port

Add a git-ignored `.wp-env.override.json` at the worktree root, e.g. `{ "port": 8910 }`,
so this instance does not collide with the host's wp-env.

### 4) Build the theme (without touching main)

Run the project's build so `build/` is fresh — use the project's build command
(e.g. `npm run build`, or the theme workspace's build script).

Caution: avoid build steps that mutate the SHARED (symlinked) `node_modules` / lockfile —
e.g. a production build that lazy-installs image-optimization deps. A non-production / dev
build is enough for layout checks and keeps the canonical checkout clean.

### 5) Start the Playground runtime

`npm run wp-env -- start --runtime=playground` (≈ `npx @wordpress/env start --runtime=playground`).

- `--runtime=playground` is a CLI flag ONLY. Putting `"runtime":"playground"` in
  `.wp-env.json` / `.wp-env.override.json` is rejected (`"runtime" is not a configuration option`).
- WASM + SQLite, no Docker. ~20-30 s to `http://localhost:<port>` returning HTTP 200.

### 6) Open the browser and log in (playwright-cli)

- `playwright-cli open http://localhost:<port>` (sandbox: add `--browser=chromium`).
- Playground's built-in auto-login does NOT apply to an external playwright browser.
  Log in via `/wp-login.php` with `admin` / `password` (wp-env defaults).

### 7) Activate the theme + seed content (UI/REST — no WP-CLI)

- Activate: `goto` the activate href on `/wp-admin/themes.php`
  (`?action=activate&stylesheet=<theme-slug>&_wpnonce=…`).
- Seed: open the block editor (`/wp-admin/post-new.php`) so `window.wp.apiFetch`
  (auto-nonce) is available, then from `playwright-cli eval`:
  - posts: `await wp.apiFetch({ path:'/wp/v2/posts', method:'POST', data:{ title, status:'publish' } })`.
    For a custom post type use its REST base (`/wp/v2/<rest-base>`); the CPT must be
    registered with `show_in_rest:true`.
  - page: `POST /wp/v2/pages` with content containing your block, e.g.
    `<!-- wp:<namespace>/<block> /-->`, then open that page.

### 8) Verify

- Screenshots: `screenshot --full-page` → output lands in `.playwright-cli/*.png`; Read it.
- Measurements: `eval` with `getComputedStyle` / `getBoundingClientRect` for exact px.
- Check both desktop and mobile via `resize` (e.g. 1440 and 390 widths).

## Gotchas

- Playground DB is a fresh, volatile SQLite store — it is wiped on restart, so steps 6–7
  (login + content seeding) must be redone every session.
- `wp-env run` (WP-CLI) is unsupported on the Playground runtime.
- Missing mapping host path = hard fail (see step 2).
- If the theme uses `@wordpress/scripts`: `wp-scripts lint-style --fix <file>` ignores the
  file arg and reformats ALL CSS — never use it to "fix one file"; revert unintended files
  with `git checkout --`.

## Cleanup

- Stop: `npm run wp-env -- stop` (or `destroy`) from the worktree root.
- Remove the worktree when done: `git worktree remove ../<repo>-<topic>`.
