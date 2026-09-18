<?php

/**
 * Tracking tag: body open
 *
 * <body> の直後に出力するタグ (GTM の noscript など) をそのまま記述する。
 *
 * タグは nowdoc (<<<'TRACKING_TAG') の内側に貼り付けること。
 * nowdoc の中身は phpcbf やエディタのフォーマッタが書き換えないため、
 * 配布元のスニペットを一字一句そのまま保てる。
 * 閉じ識別子 TRACKING_TAG は必ず行頭 (インデントなし) に置く。
 *
 * 既定の GTM スニペットはサンプル。GTM-XXXXXXX を実際のコンテナ ID に置き換えるか、
 * 不要ならタグごと削除する (空にすれば inc/tracking.php は何も出力しない)。
 *
 * @package wbs
 */

echo <<<'TRACKING_TAG'

<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-XXXXXXX"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->

TRACKING_TAG . "\n";
