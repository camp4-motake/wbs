#!/usr/bin/env bash
#
# PostToolUse hook: lint the file Claude just edited.
#
# Auto-fixes what the linters can fix (phpcbf, stylelint --fix), then reports
# whatever is left on stderr with exit code 2 so Claude has to deal with it
# before moving on. Anything it cannot lint — missing tool, file outside the
# repo, unhandled extension — exits 0 and stays out of the way.
#
# Resolves paths from its own location, so it lints against the worktree it
# lives in rather than whatever the shell's cwd happens to be.

set -uo pipefail

root="$(cd "$(dirname "${BASH_SOURCE[0]}")/../.." && pwd)"
bin="$root/node_modules/.bin"

file="$(jq -r '.tool_input.file_path // .tool_response.filePath // empty' 2>/dev/null)"
[ -n "$file" ] && [ -f "$file" ] || exit 0
case "$file" in "$root"/*) ;; *) exit 0 ;; esac

out=""
status=0

case "$file" in
	*.php)
		[ -x "$root/vendor/bin/phpcs" ] || exit 0
		"$root/vendor/bin/phpcbf" -q "$file" >/dev/null 2>&1
		out="$("$root/vendor/bin/phpcs" -q --report=emacs "$file" 2>&1)" || status=1
		;;
	*.css)
		[ -x "$bin/stylelint" ] || exit 0
		(cd "$root" && "$bin/stylelint" --fix "$file" >/dev/null 2>&1)
		out="$(cd "$root" && "$bin/stylelint" "$file" 2>&1)" || status=1
		;;
	*.ts | *.tsx)
		[ -x "$bin/tsc" ] || exit 0
		out="$(cd "$root" && "$bin/tsc" -p themes/wbs --noEmit 2>&1)" || status=1
		;;
	*)
		exit 0
		;;
esac

if [ "$status" -ne 0 ] && [ -n "$out" ]; then
	printf '%s\n' "$out" >&2
	exit 2
fi

exit 0
