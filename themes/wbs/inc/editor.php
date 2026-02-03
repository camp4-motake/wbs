<?php

/**
 * Editor Hooks
 *
 * @package wbs
 */

namespace Site\Theme\Editor;

/**
 * 投稿の初回保存時にページIDを設定（記事の日本語タイトルがそのままスラッグになるのを防ぐ）
 *
 * @see https://liginc.co.jp/576942
 */
function fix_cpt_lug( $slug, $post_ID, $post_status, $post_type ) {
	$post            = get_post( $post_ID );
	$is_slug_invalid = preg_match( '/(%[0-9a-f]{2})+/', $slug );

	if (
		$is_slug_invalid &&
		! empty( $post->post_date_gmt ) &&
		'0000-00-00 00:00:00' === $post->post_date_gmt
	) {
		if ( 'page' === $post_type ) {
			return 'page-' . $post_ID;
		}
		return $post_ID;
	}
	if ( $is_slug_invalid ) {
		if ( 'page' === $post_type ) {
			return 'page-' . $post_ID;
		}
		return $post_ID;
	}
	return $slug;
}
add_filter( 'wp_unique_post_slug', __NAMESPACE__ . '\\fix_cpt_lug', 10, 4 );

/**
 * Openverseを無効化
 * @see https://www.wppagebuilders.com/disable-openverse-wordpress/
 */
function remove_openverse( $settings ) {
	$settings['enableOpenverseMediaCategory'] = false;
	return $settings;
}
add_filter( 'block_editor_settings_all', __NAMESPACE__ . '\\remove_openverse', 10 );

/**
 * WordPressコアのブロックディレクトリパネルを非表示
 */
function disable_block_directory( $settings ) {
	$settings['__experimentalBlockDirectory'] = false;
	return $settings;
}
add_filter( 'block_editor_settings_all', __NAMESPACE__ . '\disable_block_directory' );
add_filter( 'should_load_remote_block_patterns', '__return_false' );

/**
 * WordPressコアのパターンを削除
 */
function remove_default_block_patterns() {
	remove_theme_support( 'core-block-patterns' );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\remove_default_block_patterns' );
