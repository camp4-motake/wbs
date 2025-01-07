<?php

namespace Site\Theme\CustomFields\Meta\PickupPost;

/**
 * ex
 * ブロックエディターにトグルスイッチのカスタムフィールドを追加
 */
function add_custom_toggle_field() {

	$post_types = array( 'news' );

	foreach ( $post_types as $_post_type ) {
		register_post_meta(
			$_post_type,
			'_meta_is_pickup_post',
			array(
				'show_in_rest'  => true,
				'single'        => true,
				'type'          => 'boolean',
				'default'       => false,
				'auth_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);
	}
}
add_action( 'init', __NAMESPACE__ . '\\add_custom_toggle_field' );
