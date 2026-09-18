<?php

/**
 * Tracking tag: head
 *
 * </head> の直前に出力するタグ (GTM など) をそのまま記述する。
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

<!-- Google Tag Manager -->
<script>(function(w,d,s,l,i){w[l]=w[l]||[];w[l].push({'gtm.start':
new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],
j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=
'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);
})(window,document,'script','dataLayer','GTM-XXXXXXX');</script>
<!-- End Google Tag Manager -->

TRACKING_TAG . "\n";
