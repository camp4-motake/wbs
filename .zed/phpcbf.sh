#!/usr/bin/env bash
# Zed external formatter 用 phpcbf ラッパー
# 使い方: phpcbf.sh <buffer_path>  (stdin にバッファ内容、stdout に整形結果)
#
# phpcbf は修正を行うと終了コード 1 を返すが、Zed は非 0 を失敗とみなすため
# 0〜2 (いずれも stdout に有効なコードを出力する) を成功にマップする。

# スクリプト位置からリポジトリルートを解決 (cwd に依存しない)
root="$(cd "$(dirname "${BASH_SOURCE[0]}")/.." && pwd)"

output=$("$root/vendor/bin/phpcbf" -q --standard="$root/phpcs.xml.dist" --stdin-path="$1" - 2>/dev/null)
status=$?

# 0: 修正不要 / 1: 全て修正 / 2: 一部修正不能 (出力は有効) / 3+: 処理エラー
if [ "$status" -ge 3 ] || [ -z "$output" ]; then
	exit 1 # 失敗時は Zed がバッファを変更しない
fi

# $(...) が末尾改行を削るため 1 つ戻す (WPCS はファイル末尾改行必須)
printf '%s\n' "$output"
