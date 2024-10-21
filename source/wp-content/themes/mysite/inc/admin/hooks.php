<?php

/**
 * Admin Hooks
 *
 * @package wbs
 */

namespace Site\Theme\Admin\Hooks;

/**
 * ページリストの投稿表示数を変更
 *
 * @param number $posts_per_page .
 * @return number
 */
function change_posts_per_page( $posts_per_page ) {
	if ( $posts_per_page >= 100 ) {
		return $posts_per_page;
	}
	return 100;
}
add_filter( 'edit_posts_per_page', __NAMESPACE__ . '\\change_posts_per_page' );

/**
 * 固定ページ一覧にスラッグ表示用カラムを追加
 *
 * @param array $columns .
 * @return array
 */
function add_page_column_title( $columns ) {
	$columns['slug'] = 'スラッグ';
	return $columns;
}
add_filter( 'manage_pages_columns', __NAMESPACE__ . '\\add_page_column_title' );
/* add_filter('manage_[post_type]_posts_columns', __NAMESPACE__ . '\\add_page_column_title');  */ // phpcs:ignore Squiz.PHP.CommentedOutCode.Found

/**
 * 固定ページ一覧にスラッグを表示
 *
 * @param string $column_name .
 * @param number $post_id .
 * @return void
 */
function add_page_column( $column_name, $post_id ) {
	if ( 'slug' === $column_name ) {
		$post  = get_post( $post_id );
		$uri   = get_permalink( $post_id );
		$slug  = $post->post_name;
		$error = '';

		if ( strpos( esc_attr( $slug ), '%' ) !== false ) {
			$error = '<strong class="error" style="color:red;">【!】パーマリンクのURLスラッグを半角英数字のみに修正してから公開してください。</strong>';
		}

		echo $error . '<a href="' . esc_url( $uri ) . '" target="_blank" rel="noopener">' . esc_attr( $slug ) . ' </a>';
	}
}
add_action( 'manage_pages_custom_column', __NAMESPACE__ . '\\add_page_column', 10, 2 );
/* add_action('manage_[post_type]_posts_custom_column', __NAMESPACE__ . '\\add_page_column', 10, 2); */ // phpcs:ignore Squiz.PHP.CommentedOutCode.Found

/**
 * 管理画面カスタマイズ用追加インラインCSS
 */
function add_inline_styles() {
	$style = '<style>';

	// 管理バー左上タイトルにローカルホスト・開発サーバー識別文字を追加.
	if ( wp_get_environment_type() === 'local' ) {
		$style .= '#wp-admin-bar-site-name .ab-item:after { content:"（ローカル）"; }';
	} elseif ( wp_get_environment_type() === 'staging' ) {
		$style .= '#wp-admin-bar-site-name .ab-item:after { content:"（ステージング環境）"; }';
	}
	$style .= '</style>';

	echo wp_kses(
		$style,
		array(
			'style' => array(
				'style'    => array(),
				'class'    => array(),
				'entrance' => array(),
			),
		)
	);
}
add_action( 'admin_print_styles', __NAMESPACE__ . '\\add_inline_styles' );
