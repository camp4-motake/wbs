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
		'slug'  => 'wbs-theme-blocks',
		'title' => __( 'wbsテーマ専用レイアウト', 'wbs' ),
	);

	return $categories;
}
add_filter( 'block_categories_all', __NAMESPACE__ . '\\custom_block_category' );

/**
 * Custom Pattern Category
 * @see https://ja.wordpress.org/team/handbook/block-editor/reference-guides/block-api/block-patterns/
 */
function custom_register_theme_pattern_categories() {
	register_block_pattern_category( 'wbs-theme-patterns', array( 'label' => __( 'wbsテーマ専用パターン', 'wbs' ) ) );
}
add_action( 'init', __NAMESPACE__ . '\\custom_register_theme_pattern_categories' );
