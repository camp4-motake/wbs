<?php

/* example // phpcs:ignore Squiz.PHP.CommentedOutCode.Found

namespace Site\Theme\BlockTemplate;

function register_page_block_template() {
	$target_post_types = array( 'page' );

	$block_template = array(
		array(
			'wbs/page-header',
			array(
				'lock' => array(
					'move'   => true,
					'remove' => true,
				),
			),
		),
	);

	foreach ( $target_post_types as $pt ) {
		$post_type_object = get_post_type_object( $pt );
		if ( $post_type_object ) {
			$post_type_object->template = $block_template;
		}
	}
}
add_action( 'init', __NAMESPACE__ . '\\register_page_block_template', 10 );
 */
