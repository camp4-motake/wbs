<?php

/**
 * カスタム投稿タイプ: news
 *
 * @package wbs
 */

namespace Site\Theme\CustomPostType\News;

/**
 * Config 定義(スキーマは factory.php 参照)
 *
 * @return array
 */
function get_config() {
	return array(
		'post_type'      => 'news',
		'label'          => esc_html__( 'ニュース・イベント', 'wbs' ),
		'menu_icon'      => 'dashicons-edit-large',
		'post_type_args' => array(
			'labels'   => array(
				'menu_name' => esc_html__( 'ニュース', 'wbs' ),
			),
			'supports' => array( 'title', 'author', 'editor', 'thumbnail', 'custom-fields', 'revisions' ),
		),
		'taxonomies'     => array(
			'category-news' => array(
				'label' => esc_html__( 'ニュース カテゴリー', 'wbs' ),
				'slug'  => 'categories/news',
			),
		),
	);
}
\Site\Theme\CustomPostType\Factory\register_config( __NAMESPACE__ . '\\get_config' );
