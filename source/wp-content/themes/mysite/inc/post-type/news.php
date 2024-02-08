<?php

/**
 * カスタム投稿タイプ: news
 *
 * @package wbs
 */

namespace Site\Theme\CustomPostType\News;

function init() {
	set_post_type();
	set_taxonomy();
}
add_action( 'init', __NAMESPACE__ . '\\init' );

/**
 * カスタム投稿タイプ登録
 * @see https://developer.wordpress.org/reference/functions/register_post_type/
 */
function set_post_type() {
	register_post_type(
		'news',
		array(
			'labels'        => array(
				'name'               => __( 'お知らせ', 'wbs' ),
				'singular_name'      => __( 'お知らせ', 'wbs' ),
				'menu_name'          => __( 'お知らせ', 'wbs' ),
				'add_new'            => __( 'お知らせを追加', 'wbs' ),
				'add_new_item'       => __( 'お知らせを新規追加', 'wbs' ),
				'edit_item'          => __( 'お知らせを編集する', 'wbs' ),
				'new_item'           => __( '新規お知らせ', 'wbs' ),
				'all_items'          => __( 'お知らせ一覧', 'wbs' ),
				'view_item'          => __( '投稿を見る', 'wbs' ),
				'search_items'       => __( '検索する', 'wbs' ),
				'not_found'          => __( 'お知らせが見つかりませんでした。', 'wbs' ),
				'not_found_in_trash' => __( 'ゴミ箱内にお知らせが見つかりませんでした。', 'wbs' ),
			),
			'public'        => true,
			'has_archive'   => true,
			'show_in_rest'  => true,
			'show_ui'       => true,
			'show_in_menu'  => true,
			'supports'      => array( 'title', 'author', 'editor', 'thumbnail' ),
			'rewrite'       => array( 'slug' => 'news' ),
			'menu_position' => 4,
			'menu_icon'     => 'dashicons-edit-large',
		)
	);
}

/**
 * カスタムタクソノミー登録
 * @see https://developer.wordpress.org/reference/functions/register_taxonomy/
 */
function set_taxonomy() {
	register_taxonomy(
		'category-news',
		array( 'news' ),
		array(
			'public'       => true,
			'show_ui'      => true,
			'hierarchical' => true,
			'show_in_rest' => true,
			'labels'       => array(
				'name'              => __( 'お知らせカテゴリー', 'wbs' ),
				'singular_name'     => __( 'お知らせカテゴリー', 'wbs' ),
				'menu_name'         => __( 'お知らせカテゴリー', 'wbs' ),
				'search_items'      => __( 'お知らせカテゴリーを検索', 'wbs' ),
				'all_items'         => __( '全てのお知らせカテゴリー', 'wbs' ),
				'parent_item'       => __( '親お知らせカテゴリー', 'wbs' ),
				'parent_item_colon' => __( '親お知らせカテゴリー:', 'wbs' ),
				'edit_item'         => __( 'お知らせカテゴリーを編集', 'wbs' ),
				'update_item'       => __( 'お知らせカテゴリーを更新', 'wbs' ),
				'add_new_item'      => __( 'お知らせカテゴリーを追加', 'wbs' ),
				'new_item_name'     => __( '新規お知らせカテゴリー', 'wbs' ),
			),
			'rewrite'      => array(
				'slug'         => 'categories/news',
				'with_front'   => false,
				'hierarchical' => true,
			),
			// 'capabilities' => array(
			//  'assign_terms' => 'edit_newss',
			// ),
		)
	);
}

/**
 * 管理画面 投稿リストにカスタムタクソノミー絞り込みドロップダウンを追加
 */
function set_custom_filter_dropdown() {

	global $post_type;

	if ( 'news' !== $post_type ) {
		return;
	}
	wp_dropdown_categories(
		array(
			'show_option_all' => __( 'すべてのカテゴリー', 'wbs' ),
			'orderby'         => 'name',
			'selected'        => get_query_var( 'category-news' ),
			'hide_empty'      => true,
			'name'            => 'category-news',
			'taxonomy'        => 'category-news',
			'value_field'     => 'slug',
		)
	);
}
add_action( 'restrict_manage_posts', __NAMESPACE__ . '\\set_custom_filter_dropdown' );
