<?php

/**
 * Post type Archive link
 */

$ctx = wp_parse_args(
	$args,
	array(
		'post_type' => 'topics',
		'label'     => __( '一覧', 'wbs' ),
	)
);

$post_type_obj = get_post_type_object( $ctx['post_type'] );

if ( ! $post_type_obj ) {
	return;
}

$btn = '
<!-- wp:buttons {"layout":{"type":"flex","justifyContent":"center"},"style":{"spacing":{"margin":{"top":"var:preset|spacing|lg"}}}} -->
<div class="wp-block-buttons" style="margin-top:var(--wp--preset--spacing--lg)">
	<!-- wp:button -->
	<div class="wp-block-button">
		<a class="wp-block-button__link wp-element-button" href="' . esc_url( get_post_type_archive_link( $ctx['post_type'] ) ) . '">' . esc_html( $ctx['label'] ) . '</a>
	</div>
	<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
';

echo wp_kses_post( do_blocks( $btn ) );
