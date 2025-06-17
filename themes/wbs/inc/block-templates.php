<?php

namespace Site\Theme\BlockTemplate;

function register_block_template() {

	$target_post_types = array();

	foreach ( $target_post_types as $pt ) {
		$post_type_object = get_post_type_object( $pt );

		if ( ! $post_type_object ) {
			continue;
		}

		$post_type_object->template = array();
	}
}
add_action( 'init', __NAMESPACE__ . '\\register_block_template', 10 );
