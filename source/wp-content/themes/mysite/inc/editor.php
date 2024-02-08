<?php

/**
 * Editor Hooks
 *
 * @package wbs
 */

/**
 * Enqueue editor assets
 *
 * @return void
 */
function enqueue_editor_assets() {

	$asset = include_once get_theme_file_path( 'build/editor/index.asset.php' );

	wp_enqueue_style(
		'theme-editor-styles',
		get_theme_file_uri( 'build/editor/index.css' ),
		array(),
		$asset['version']
	);

	wp_enqueue_script(
		'theme-editor-scripts',
		get_theme_file_uri( 'build/editor/index.js' ),
		array(),
		$asset['version'],
		false
	);
}
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\enqueue_editor_assets', 100 );


/**
 * 投稿の初回保存時にページIDを設定（記事の日本語タイトルがそのままスラッグになるのを防ぐ）
 *
 * @see https://liginc.co.jp/576942
 */
function fix_cpt_lug( $slug, $post_ID, $post_status, $post_type ) {
	$post            = get_post( $post_ID );
	$is_slug_invalid = preg_match( '/(%[0-9a-f]{2})+/', $slug );

	if ( $is_slug_invalid && $post->post_date_gmt === '0000-00-00 00:00:00' ) {
		if ( $post_type === 'page' ) {
			return 'page-' . $post_ID;
		}
		return $post_ID;
	}
	if ( $is_slug_invalid ) {
		if ( $post_type === 'page' ) {
			return 'page-' . $post_ID;
		}
		return $post_ID;
	}
	return $slug;
}
add_filter( 'wp_unique_post_slug', __NAMESPACE__ . '\\fix_cpt_lug', 10, 4 );
