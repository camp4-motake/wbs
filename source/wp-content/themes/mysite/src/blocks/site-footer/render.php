<?php

/**
 * Footer
 *
 * @package wbs
 */

$is_rest_request      = defined( 'REST_REQUEST' ) && REST_REQUEST;
$attr                 = array( 'class' => 'footer' );
$inner_blocks_content = $content;

if ( empty( $inner_blocks_content ) && ! empty( $attributes['innerContent'] ) ) {
	$inner_blocks_content = $attributes['innerContent'];
}

?>
<div <?php echo wp_kses_post( get_block_wrapper_attributes( $attr ) ); ?>>
	<div class="footer-body">
		<!-- TODO -->
	</div>
	<?php get_template_part( 'src/blocks/site-footer/copyright' ); ?>
</div>