<?php

/**
 * Custom Menu Order
 *
 * @package wbs
 */

namespace Site\Theme\Admin\MenuOrder;

add_action( 'init', __NAMESPACE__ . '\\init' );

/**
 * Init
 *
 * @return void
 */
function init() {
	add_filter( 'custom_menu_order', __NAMESPACE__ . '\\custom_menu_order' );
	add_filter( 'menu_order', __NAMESPACE__ . '\\custom_menu_order' );
	// add_custom_menu_links();
}

/**
 * カスタム投稿タイプの管理画面メニュー順序を変更
 *
 * @param array $menu_ord .
 * @return mixture
 */
function custom_menu_order( $menu_ord ) {
	if ( ! $menu_ord ) {
		return true;
	}

	return array(
		'index.php', // ダッシュボード.
		'separator1',
		'edit.php?post_type=page', // 固定ページ.
		'edit.php?post_type=news',
		'separator2',
	);
}

/**
 * カスタムリンクを追加
 */
/*
function add_custom_menu_links() {

	if ( ! current_user_can( 'manage_categories' ) ) {
		return;
	}

	add_action(
		'admin_menu',
		function () {
			add_menu_page( 'ホーム', 'ホーム', 'read', get_frontpage_url(), null, 'dashicons-edit-page', 10 );
		},
		10
	);
}
 */

/**
 * フロントページの編集URLを取得
 *
 * @return [string] URL
 */
function get_frontpage_url() {

	$front_page_id = get_option( 'page_on_front' );
	$url           = get_edit_post_link( $front_page_id );

	return $url ? $url : null;
}
