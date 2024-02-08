<?php

/**
 * Register Custom Blocks
 *
 * @package wbs
 */

namespace Site\Theme\RegisterBlocks;

/**
 * register Blocks
 */
function create_block_wbs_blocks_block_init() {

	// コアブロックパターン削除
	remove_theme_support( 'core-block-patterns' );

	// テーマ用ブロック登録
	foreach ( glob( get_theme_file_path( 'build/blocks/*' ) ) as $block ) {
		register_block_type( $block );
	}
}
add_action( 'init', __NAMESPACE__ . '\\create_block_wbs_blocks_block_init' );

/**
 * custom block category
 */
function custom_block_category( $categories ) {
	$categories[] = array(
		'slug'  => 'wbs-blocks',
		'title' => __( 'サイトレイアウト', 'wbs' ),
	);

	return $categories;
}
add_filter( 'block_categories_all', __NAMESPACE__ . '\\custom_block_category' );


/**
 * remove Openverse
 * @see https://www.wppagebuilders.com/disable-openverse-wordpress/
 */
function remove_openverse( $settings ) {
	$settings['enableOpenverseMediaCategory'] = false;
	return $settings;
}
add_filter( 'block_editor_settings_all', __NAMESPACE__ . '\\remove_openverse', 10 );
