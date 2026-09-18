<?php

/**
 * page header
 *
 * @package wbs
 *
 * @param array $attributes - A clean associative array of block attributes.
 * @param array $block - All the block settings and attributes.
 * @param string $content - The block inner HTML (usually empty unless using inner blocks).
 */

$extra_attributes = array();

// パンくずは SEO プラグインのショートコードに委ねる。プラグインの有効状態ではなく
// ショートコードの登録有無で判定する: is_plugin_active() は wp-admin/includes/plugin.php
// でしか定義されず、フロントのブロック描画中に呼ぶと undefined function になる。
$breadcrumb_shortcodes = array(
	'tsf_breadcrumb',   // The SEO Framework
	'wpseo_breadcrumb', // Yoast SEO
);

$breadcrumb_shortcode = '';
foreach ( $breadcrumb_shortcodes as $shortcode_tag ) {
	if ( shortcode_exists( $shortcode_tag ) ) {
		$breadcrumb_shortcode = '[' . $shortcode_tag . ']';
		break;
	}
}

$page_header_title = get_the_title();
if ( ! empty( $attributes['title'] ) ) {
	$page_header_title = $attributes['title'];
} elseif ( ! is_page() && ( is_singular() || is_post_type_archive() ) ) {
	$_object = get_post_type_object( get_post_type() );

	if ( ! empty( $_object->labels->singular_name ) ) {
		$page_header_title = $_object->labels->singular_name;
	}
}

$is_h1 = ! is_singular( array( 'news' ) );

?>
<div <?php echo wp_kses_post( get_block_wrapper_attributes( $extra_attributes ) ); ?>>
	<div class="circle" role="presentation"></div>
	<div class="page-header-container">
		<?php if ( ! empty( $breadcrumb_shortcode ) ) : ?>
			<div class="breadcrumbs"><?php echo do_shortcode( $breadcrumb_shortcode ); ?></div>
		<?php endif; ?>
		<hgroup class="heading-group">
			<?php if ( $is_h1 ) : ?>
				<h1 class="heading"><span><?php echo esc_html( $page_header_title ); ?></span></h1>
			<?php else : ?>
				<div class="heading h1"><span><?php echo esc_html( $page_header_title ); ?></span></div>
			<?php endif; ?>
		</hgroup>
	</div>
</div>