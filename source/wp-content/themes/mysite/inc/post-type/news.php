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
				'name'               => __( 'ニュース', 'wbs' ),
				'singular_name'      => __( 'ニュース', 'wbs' ),
				'menu_name'          => __( 'ニュース', 'wbs' ),
				'add_new'            => __( 'ニュースを追加', 'wbs' ),
				'add_new_item'       => __( 'ニュースを新規追加', 'wbs' ),
				'edit_item'          => __( 'ニュースを編集する', 'wbs' ),
				'new_item'           => __( '新規ニュース', 'wbs' ),
				'all_items'          => __( 'ニュース一覧', 'wbs' ),
				'view_item'          => __( '投稿を見る', 'wbs' ),
				'search_items'       => __( '検索する', 'wbs' ),
				'not_found'          => __( 'ニュースが見つかりませんでした。', 'wbs' ),
				'not_found_in_trash' => __( 'ゴミ箱内にニュースが見つかりませんでした。', 'wbs' ),
			),
			'public'        => true,
			'has_archive'   => true,
			'show_in_rest'  => true,
			'show_ui'       => true,
			'show_in_menu'  => true,
			'supports'      => array( 'title', 'author', 'editor', 'thumbnail', 'custom-fields', 'revisions' ),
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
				'name'              => __( 'ニュースカテゴリー', 'wbs' ),
				'singular_name'     => __( 'ニュースカテゴリー', 'wbs' ),
				'menu_name'         => __( 'ニュースカテゴリー', 'wbs' ),
				'search_items'      => __( 'ニュースカテゴリーを検索', 'wbs' ),
				'all_items'         => __( '全てのニュースカテゴリー', 'wbs' ),
				'parent_item'       => __( '親ニュースカテゴリー', 'wbs' ),
				'parent_item_colon' => __( '親ニュースカテゴリー:', 'wbs' ),
				'edit_item'         => __( 'ニュースカテゴリーを編集', 'wbs' ),
				'update_item'       => __( 'ニュースカテゴリーを更新', 'wbs' ),
				'add_new_item'      => __( 'ニュースカテゴリーを追加', 'wbs' ),
				'new_item_name'     => __( '新規ニュースカテゴリー', 'wbs' ),
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
