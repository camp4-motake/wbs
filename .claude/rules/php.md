---
paths: "**/*.php"
---

# WordPress and PHP Rules

- Follow WPCS: tabs, Yoda conditions, `snake_case`, single quotes, strict comparison (`===`/`!==`), short array `[]`, type declarations
- Never shadow WP globals (`$post`, `$wp_query`, `$wpdb`, `$wp`, `$current_user`, `$pagenow`, `$page`, `$pages`, etc.) — use prefixed names (`$event_post`, `$item`, `$entry`)
- Explicit `global` declaration when accessing globals; restore state with `wp_reset_postdata()`
- Follow WPCS hints and recommendations — prefer WP alternatives and safe patterns, e.g.:
  - HTTP: `wp_remote_get()` / `wp_remote_post()` instead of `file_get_contents()` / `curl_*`
  - JSON / random: `wp_json_encode()` instead of `json_encode()`, `wp_rand()` instead of `rand()` / `mt_rand()`
  - DB: always use `$wpdb->prepare()` for any query with variables
  - Output: always escape — `esc_html()`, `esc_attr()`, `esc_url()`, `esc_textarea()`, `wp_kses_post()` (never echo raw input/meta/option values)
  - Input: `wp_unslash()` + appropriate `sanitize_*()` / `absint()` on `$_GET` / `$_POST` / `$_REQUEST` / `$_COOKIE`; verify with `wp_verify_nonce()` / `check_admin_referer()` / `current_user_can()`
  - Filesystem: use `WP_Filesystem` API instead of `file_put_contents()` / `fopen()` / `unlink()`
  - Date/time: `current_time()` / `wp_date()` instead of `date()` / `time()` where WP context matters
- Never suppress WPCS errors with `phpcs:ignore` / `phpcs:disable` / `phpcs:ignoreFile` comments — fix the underlying issue (use the WP alternative, escape output, sanitize/validate input, add nonce, etc.)
- Never modify `phpcs.xml.dist` ruleset without explicit user instruction — fix the code to comply with existing rules instead of relaxing them
