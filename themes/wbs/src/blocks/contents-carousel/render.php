<?php

/**
 * contents-carousel
 *
 * @package wbs
 *
 * @param array $attributes - A clean associative array of block attributes.
 * @param array $block - All the block settings and attributes.
 * @param string $content - The block inner HTML (usually empty unless using inner blocks).
 */

$is_rest_request  = defined( 'REST_REQUEST' ) && REST_REQUEST;
$extra_attributes = array();

$inner_blocks_content = $content;
if ( $is_rest_request && empty( $content && ! empty( $attributes['innerContent'] ) ) ) {
	$inner_blocks_content = $attributes['innerContent'];
}

$slider_context = array(
	'isCarouselActive' => false,
	'api'              => null,
	'el'               => null,
);

?>
<div <?php echo wp_kses_post( get_block_wrapper_attributes( $extra_attributes ) ); ?>>
	<div
		class="carousel-container"
		data-wp-interactive="contentsCarousel"
		data-wp-init="callbacks.initCarousel"
		data-wp-class--is-carousel-active="context.isCarouselActive"
		<?php echo wp_kses_post( wp_interactivity_data_wp_context( $slider_context ) ); ?>>
		<div class="carousel-inner">
			<div class="swiper">
				<div class="swiper-wrapper">
					<?php echo wp_kses_post( do_blocks( $inner_blocks_content ) ); ?>
				</div>
			</div>
			<div class="swiper-button-prev"></div>
			<div class="swiper-button-next"></div>
		</div>
		<div class="carousel-footer">
			<div class="swiper-pagination"></div>
		</div>
	</div>
</div>
