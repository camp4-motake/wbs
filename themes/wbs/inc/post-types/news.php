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
				'name'               => esc_html__( 'ニュース・イベント', 'wbs' ),
				'singular_name'      => esc_html__( 'ニュース・イベント', 'wbs' ),
				'menu_name'          => esc_html__( 'ニュース', 'wbs' ),
				'add_new'            => esc_html__( 'ニュース・イベントを追加', 'wbs' ),
				'add_new_item'       => esc_html__( 'ニュース・イベントを新規追加', 'wbs' ),
				'edit_item'          => esc_html__( 'ニュース・イベントを編集する', 'wbs' ),
				'new_item'           => esc_html__( '新規ニュース・イベント', 'wbs' ),
				'all_items'          => esc_html__( 'ニュース・イベント一覧', 'wbs' ),
				'view_item'          => esc_html__( '投稿を見る', 'wbs' ),
				'search_items'       => esc_html__( '検索する', 'wbs' ),
				'not_found'          => esc_html__( 'ニュース・イベントが見つかりませんでした。', 'wbs' ),
				'not_found_in_trash' => esc_html__( 'ゴミ箱内にニュース・イベントが見つかりませんでした。', 'wbs' ),
			),
			'public'        => true,
			'has_archive'   => true,
			'show_in_rest'  => true,
			'show_ui'       => true,
			'show_in_menu'  => true,
			'supports'      => array( 'title', 'author', 'editor', 'thumbnail', 'custom-fields', 'revisions' ),
			'rewrite'       => array( 'slug' => 'news' ),
			'menu_position' => 0,
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
				'name'              => esc_html__( 'ニュース カテゴリー', 'wbs' ),
				'singular_name'     => esc_html__( 'ニュース カテゴリー', 'wbs' ),
				'menu_name'         => esc_html__( 'ニュース カテゴリー', 'wbs' ),
				'search_items'      => esc_html__( 'ニュース カテゴリーを検索', 'wbs' ),
				'all_items'         => esc_html__( '全てのニュース カテゴリー', 'wbs' ),
				'parent_item'       => esc_html__( '親ニュース カテゴリー', 'wbs' ),
				'parent_item_colon' => esc_html__( '親ニュース カテゴリー:', 'wbs' ),
				'edit_item'         => esc_html__( 'ニュース カテゴリーを編集', 'wbs' ),
				'update_item'       => esc_html__( 'ニュース カテゴリーを更新', 'wbs' ),
				'add_new_item'      => esc_html__( 'ニュース カテゴリーを追加', 'wbs' ),
				'new_item_name'     => esc_html__( '新規ニュース カテゴリー', 'wbs' ),
			),
			'rewrite'      => array(
				'slug'         => 'categories/news',
				'with_front'   => false,
				'hierarchical' => true,
			),
		)
	);
}

// カスタムタクソノミーのリライトルールを追加
function custom_news_taxonomy_rewrite_rules( $rules ) {
	$new_rules = array();

	$new_rules['categories/news/([^/]+)/?$']                   = 'index.php?category-news=$matches[1]';
	$new_rules['categories/news/([^/]+)/page/?([0-9]{1,})/?$'] = 'index.php?category-news=$matches[1]&paged=$matches[2]';

	return $new_rules + $rules;
}
add_filter( 'rewrite_rules_array', __NAMESPACE__ . '\\custom_news_taxonomy_rewrite_rules' );


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
			'show_option_all' => esc_html__( 'すべてのカテゴリー', 'wbs' ),
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
