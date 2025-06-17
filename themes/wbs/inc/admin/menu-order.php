<?php

/**
 * Custom Menu Order
 *
 * @package wbs
 */

namespace Site\Theme\Admin\MenuOrder;

add_action( 'init', __NAMESPACE__ . '\\init' );

function init() {
	add_filter( 'custom_menu_order', __NAMESPACE__ . '\\custom_menu_order' );
	add_filter( 'menu_order', __NAMESPACE__ . '\\custom_menu_order' );
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
