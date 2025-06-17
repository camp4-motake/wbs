<?php

/**
 * main article loop
 *
 * @package wbs
 */

$is_rest_request = defined( 'REST_REQUEST' ) && REST_REQUEST;

$query_args = array(
	'post_type'              => $attributes['postTypeSlug'],
	'posts_per_page'         => $attributes['postPerPage'],
	'no_found_rows'          => true,
	'update_post_meta_cache' => false,
	'update_post_term_cache' => false,
);
if (
	! empty( $attributes['taxonomySlug'] ) &&
	! empty( $attributes['termID'] )
) {
	$query_args['tax_query'] = array(
		array(
			'taxonomy' => $attributes['taxonomySlug'],
			'field'    => 'term_id',
			'terms'    => $attributes['termID'],
		),
	);
}
$the_query = new WP_Query( $query_args );

$post_count = '';
if ( $the_query->post_count ) {
	$post_count = $the_query->post_count;
}

$extra_attributes = array( 'class' => 'archive-articles has-post-type-' . $attributes['postTypeSlug'] );

?>
<div <?php echo wp_kses_post( get_block_wrapper_attributes( $extra_attributes ) ); ?>>
	<div class="archive-grid" data-post-count="<?php echo esc_attr( $post_count ); ?>">
		<?php

		if ( ! $the_query->have_posts() && $is_rest_request ) {
			get_template_part( 'partials/no-posts', null, array( 'no_post_label' => __( '該当する記事がありません', 'wbs' ) ) );
		}

		if ( $the_query->have_posts() ) {

			while ( $the_query->have_posts() ) {
				$the_query->the_post();
				get_template_part( 'partials/content', get_post_type() );
			}

			wp_reset_postdata();
		}

		?>
	</div>
</div>
