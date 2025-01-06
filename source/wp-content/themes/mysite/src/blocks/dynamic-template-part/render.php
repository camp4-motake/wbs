<?php

/**
 * Dynamic Template loader
 *
 * @package wbs
 *
 * @param array $attributes - A clean associative array of block attributes.
 * @param array $block - All the block settings and attributes.
 * @param string $content - The block inner HTML (usually empty unless using inner blocks).
 */

use Site\Theme\Helper\Util;

$is_rest_request  = defined( 'REST_REQUEST' ) && REST_REQUEST;
$extra_attributes = array( 'class' => 'has-display-content' );

?>
<div <?php echo wp_kses_post( get_block_wrapper_attributes( $extra_attributes ) ); ?>>
	<?php

	// ssr dynamic template branch

	if ( $is_rest_request ) :
		echo '<div class="block-attention text-center">Dynamic Template Area</div>';

	elseif ( ! empty( $attributes['templatePartPath'] ) ) :
		get_template_part( esc_attr( $attributes['templatePartPath'] ) );

	elseif ( is_front_page() || is_home() ) :
		get_template_part( 'template-parts/front-page' );

	elseif ( is_page() ) :
		global $post;
		get_template_part( 'template-parts/page', $post->post_name );

	elseif ( is_singular() ) :
		get_template_part( 'template-parts/single', get_post_type() );

	elseif ( is_tax() ) :
		$tax_slug = get_query_var( 'taxonomy' );

		if ( Util\check_template_part_exists( 'template-parts/taxonomy' . $tax_slug ) ) :
			get_template_part( 'template-parts/taxonomy', $tax_slug );
		else :
			get_template_part( 'template-parts/archive', get_post_type() );
		endif;

	elseif ( is_archive() ) :
		get_template_part( 'template-parts/archive', get_post_type() );

	elseif ( is_search() ) :
		get_template_part( 'template-parts/search' );

	else :
		the_content();

	endif;

	?>
</div>