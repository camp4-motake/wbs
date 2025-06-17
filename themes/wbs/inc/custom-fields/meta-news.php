<?php

/**
 * meta news
 *
 * @package wbs
 */

namespace Site\Theme\CustomFields\Meta\News;

/**
 * register post meta
 *
 * @return void
 */
function add_custom_field_meta() {
	$post_types = array( 'news' );

	foreach ( $post_types as $_post_type ) :

		// リンク
		register_post_meta(
			$_post_type,
			'wbs_external_link',
			array(
				'type'          => 'object',
				'single'        => true,
				'show_in_rest'  => array(
					'schema' => array(
						'type'       => 'object',
						'properties' => array(
							'url'    => array( 'type' => 'string' ),
							'title'  => array( 'type' => 'string' ),
							'target' => array( 'type' => 'string' ),
							'rel'    => array(
								'type'  => 'array',
								'items' => array( 'type' => 'string' ),
							),
						),
					),
				),
				'auth_callback' => function () {
					return current_user_can( 'edit_posts' );
				},
			)
		);

	endforeach;
}
add_action( 'init', __NAMESPACE__ . '\\add_custom_field_meta' );

/**
 * editor assets
 *
 * @return void
 */
function enqueue_custom_sidebar_plugin() {
	$allowed_post_types = array( 'news' );

	if ( ! in_array( get_post_type(), $allowed_post_types, true ) ) {
		return;
	}

	$asset_file = include get_theme_file_path( 'build/custom-fields/news/index.asset.php' );

	wp_enqueue_script(
		'custom-fields-panel-news-scripts',
		get_theme_file_uri( 'build/custom-fields/news/index.js' ),
		$asset_file['dependencies'],
		$asset_file['version'],
		false
	);
}
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\enqueue_custom_sidebar_plugin' );
