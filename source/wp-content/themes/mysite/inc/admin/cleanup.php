<?php

/**
 * Admin Cleanup
 *
 * @package wbs
 */

namespace Site\Theme\Admin\Cleanup;

/**
 * 管理画面の不要メニューを管理者権限以外は非表示
 */
function remove_admin_menu() {
	global $menu;
	unset( $menu[5] ); // 投稿
	unset( $menu[25] ); // コメント

	if ( ! current_user_can( 'edit_files' ) ) {
		// unset($menu[2]);  // ダッシュボード
		// unset($menu[4]);  // メニューの線1
		// unset($menu[5]);  // 投稿
		// unset($menu[10]); // メディア
		// unset($menu[15]); // リンク
		// unset($menu[20]); // ページ
		// unset($menu[25]); // コメント
		// unset($menu[59]); // メニューの線2
		// unset($menu[60]); // テーマ
		// unset($menu[65]); // プラグイン
		// unset($menu[70]); // プロフィール
		unset( $menu[75] ); // ツール
		// unset($menu[80]); // 設定
		// unset($menu[90]); // メニューの線3

		remove_menu_page( 'wpcf7' );
		remove_menu_page( 'edit.php?post_type=mw-wp-form' );
		remove_menu_page( 'wpseo_workouts' );
	}

	// 　カスタムサブメニュー
	remove_submenu_page(
		'themes.php',
		'customize.php?return=' . rawurlencode( $_SERVER['REQUEST_URI'] )
	);
}
add_action( 'admin_menu', __NAMESPACE__ . '\\remove_admin_menu' );

/**
 * 不要なメニューを削除
 */
add_action(
	'admin_bar_menu',
	function ( $wp_admin_bar ) {
		// WordPressアイコン.
		$wp_admin_bar->remove_menu( 'wp-logo' );
		// //WordPressアイコン -> WordPress について
		$wp_admin_bar->remove_menu( 'about' );
		// //WordPressアイコン -> WordPress.org
		$wp_admin_bar->remove_menu( 'wporg' );
		// //WordPressアイコン -> ドキュメンテーション
		$wp_admin_bar->remove_menu( 'documentation' );
		// //WordPressアイコン -> サポートフォーラム
		$wp_admin_bar->remove_menu( 'support-forums' );
		// //WordPressアイコン -> フィードバック
		$wp_admin_bar->remove_menu( 'feedback' );

		// // サイト情報.
		// $wp_admin_bar->remove_menu( 'site-name' );
		// //サイト情報 -> ダッシュボード
		// $wp_admin_bar->remove_menu( 'dashboard' );
		// //サイト情報 -> テーマ
		// $wp_admin_bar->remove_menu( 'themes' );
		// //サイト情報 -> ウィジェット
		// $wp_admin_bar->remove_menu( 'widgets' );
		// //サイト情報 -> メニュー
		// $wp_admin_bar->remove_menu( 'menus' );
		// //サイト情報 -> ヘッダー
		// $wp_admin_bar->remove_menu( 'header' );

		// カスタマイズ
		$wp_admin_bar->remove_menu( 'customize' );

		// コメント.
		$wp_admin_bar->remove_menu( 'comments' );

		// // 新規
		// $wp_admin_bar->remove_menu( 'new-content' );
		// // 新規 -> 投稿
		// $wp_admin_bar->remove_menu( 'new-post' );
		// // 新規 -> メディア
		// $wp_admin_bar->remove_menu( 'new-media' );
		// // 新規 -> 固定ページ
		// $wp_admin_bar->remove_menu( 'new-page' );
		// // 新規 -> ユーザー
		// $wp_admin_bar->remove_menu( 'new-user' );

		// //〜の編集
		// $wp_admin_bar->remove_menu( 'edit' );

		// //こんにちは、[ユーザー名]　さん
		// $wp_admin_bar->remove_menu( 'my-account' );
		// //ユーザー -> ユーザー名・アイコン
		// $wp_admin_bar->remove_menu( 'user-info' );
		// //ユーザー -> プロフィールを編集
		// $wp_admin_bar->remove_menu( 'edit-profile' );
		// //ユーザー -> ログアウト
		// $wp_admin_bar->remove_menu( 'logout' );

		// //検索
		// $wp_admin_bar->remove_menu( 'search' );
	},
	99
);

/**
 * 不要なダッシュボードウィジットを削除
 */
add_action(
	'wp_dashboard_setup',
	function () {
		// remove_meta_box( 'dashboard_right_now', 'dashboard', 'normal' ); // 概要
		// remove_meta_box( 'dashboard_activity', 'dashboard', 'normal' ); // アクティビティ
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' ); // クイックドラフト
		remove_meta_box( 'dashboard_primary', 'dashboard', 'side' ); // WordPressニュース
	}
);

/**
 * ダッシュボードの「ヘルプ」タブを削除
 */
add_action(
	'admin_head-index.php',
	function () {
		$current_screen = get_current_screen();
		if ( isset( $current_screen ) && 'dashboard' === $current_screen->id ) {
			$current_screen->remove_help_tabs();
		}
	},
	100,
	3
);

/**
 * ビルトインの「投稿」の各メニューを非表示
 */
// Remove side menu.
add_action(
	'admin_menu',
	function () {
		remove_menu_page( 'edit.php' );
	}
);
// Remove +New post in top Admin Menu Bar.
add_action(
	'admin_bar_menu',
	function ( $wp_admin_bar ) {
		$wp_admin_bar->remove_node( 'new-post' );
	},
	999
);
// Remove Quick Draft Dashboard Widget.
add_action(
	'wp_dashboard_setup',
	function () {
		remove_meta_box( 'dashboard_quick_press', 'dashboard', 'side' );
	},
	999
);
// Remove Built in tag & category.
add_action(
	'init',
	function () {
		unregister_taxonomy_for_object_type( 'post_tag', 'post' );
		unregister_taxonomy_for_object_type( 'category', 'post' );
	}
);
