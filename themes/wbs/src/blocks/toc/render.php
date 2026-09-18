<?php

/**
 * TOC Block - Server-side rendering.
 *
 * @var array    $attributes Block attributes.
 * @var string   $content    Inner block content.
 * @var WP_Block $block      Block instance.
 *
 * @package wbs
 */

$min_level = $attributes['minLevel'] ?? 2;
$max_level = $attributes['maxLevel'] ?? 4;

// 再帰防止: 下の do_blocks() が本文中の TOC ブロック自体を再描画して無限再帰に
// 陥るのを防ぐ。file ベースの render テンプレートでは関数 static が再入時に保持
// されないため、確実に永続するグローバルフラグでガードする。
if ( ! empty( $GLOBALS['wbs_toc_rendering'] ) ) {
	return;
}

// 投稿コンテンツを取得（REST APIプレビュー時は空の場合あり）
$post_content = get_the_content();
if ( ! $post_content ) {
	return;
}

// ダイナミックブロック（accordion-loop 等）を含む全ブロックをレンダリング
$GLOBALS['wbs_toc_rendering'] = true;
$rendered_content             = do_blocks( $post_content );
$GLOBALS['wbs_toc_rendering'] = false;

// レンダリング後の HTML からすべての見出しタグを抽出
$pattern = '/<h([1-6])([^>]*)>(.*?)<\/h\1>/si';
if ( ! preg_match_all( $pattern, $rendered_content, $matches, PREG_SET_ORDER ) ) {
	return;
}

$headings = array();
foreach ( $matches as $index => $match ) {
	$level = (int) $match[1];
	if ( $level < $min_level || $level > $max_level ) {
		continue;
	}

	// id 属性があればそれを使用、なければテキストからスラッグを生成
	$anchor = '';
	$attr   = $match[2];
	if ( preg_match( '/id=["\']([^"\']+)["\']/', $attr, $id_match ) ) {
		$anchor = $id_match[1];
	}

	// WORKAROUND: アコーディオンブロックのトグルアイコンを除外してからテキスト抽出
	$inner = preg_replace( '/<span[^>]*(?:wp-block-accordion-heading__toggle-icon|_accordion-icon)[^>]*>.*?<\/span>/si', '', $match[3] );
	$text  = wp_strip_all_tags( $inner );
	if ( ! $text ) {
		continue;
	}

	if ( ! $anchor ) {
		$anchor = 'heading-' . $level . '-' . ( $index + 1 );
	}

	$headings[] = array(
		'level' => $level,
		'id'    => $anchor,
		'text'  => $text,
	);
}

if ( empty( $headings ) ) {
	return;
}

// Interactivity API: 本文見出しへのアンカー id 付与を view module の callbacks.init で行う。
$wrapper = get_block_wrapper_attributes(
	array(
		'data-wp-interactive' => 'wbs/toc',
		'data-wp-init'        => 'callbacks.init',
	)
);

?>
<nav <?php echo wp_kses_post( $wrapper ); ?> aria-label="<?php esc_attr_e( '目次', 'wbs' ); ?>">
	<div class="toc__title"><?php esc_html_e( '目次', 'wbs' ); ?></div>
	<ol class="toc__list">
		<?php foreach ( $headings as $heading ) : ?>
			<li class="<?php echo esc_attr( 'toc__item is-toc-level-' . $heading['level'] ); ?>" style="--toc-depth: <?php echo esc_attr( $heading['level'] - $min_level ); ?>">
				<a class="toc__link" href="#<?php echo esc_attr( $heading['id'] ); ?>">
					<span><?php echo esc_html( $heading['text'] ); ?></span>
				</a>
			</li>
		<?php endforeach; ?>
	</ol>
</nav>
