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

$is_rest_request  = defined( 'REST_REQUEST' ) && REST_REQUEST;
$extra_attributes = array();

$plugin_shortcodes = array(
	'autodescription/autodescription.php' => '[tsf_breadcrumb]',
	'wordpress-seo/wp-seo.php'            => '[wpseo_breadcrumb]',
);

$breadcrumb_shortcode = '';
foreach ( $plugin_shortcodes as $plugin_path => $shortcode ) {
	if ( is_plugin_active( $plugin_path ) ) {
		$breadcrumb_shortcode = $shortcode;
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