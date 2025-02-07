<?php

/**
 * Editor Hooks
 *
 * @package wbs
 */

namespace Site\Theme\Editor;

/**
 * Enqueue editor assets
 *
 * @return void
 */
function enqueue_editor_assets() {
	if ( ! is_admin() ) {
		return;
	}

	// editor assets
	$asset        = include_once get_theme_file_path( 'build/editor/index.asset.php' );
	$dependencies = ( ! empty( $asset['dependencies'] ) ) ? $asset['dependencies'] : array();
	$version      = ( ! empty( $asset['version'] ) ) ? $asset['version'] : null;

	wp_enqueue_style(
		'theme-editor-styles',
		get_theme_file_uri( 'build/editor/index.css' ),
		array(),
		$version,
	);
	wp_enqueue_script(
		'theme-editor-scripts',
		get_theme_file_uri( 'build/editor/index.js' ),
		$dependencies,
		$version,
		false
	);

	// block styles css
	$asset        = include get_theme_file_path( 'build/block-styles/index.asset.php' );
	$dependencies = ( ! empty( $asset['dependencies'] ) ) ? $asset['dependencies'] : array();
	$version      = ( ! empty( $asset['version'] ) ) ? $asset['version'] : null;

	wp_enqueue_style(
		'theme-block-styles-styles',
		get_theme_file_uri( 'build/block-styles/index.css' ),
		array(),
		$version,
	);
	wp_enqueue_script(
		'theme-block-styles-scripts',
		get_theme_file_uri( 'build/block-styles/index.js' ),
		$dependencies,
		$version,
		false
	);

	// block variations css
	$asset        = include get_theme_file_path( 'build/block-variations/index.asset.php' );
	$dependencies = ( ! empty( $asset['dependencies'] ) ) ? $asset['dependencies'] : array();
	$version      = ( ! empty( $asset['version'] ) ) ? $asset['version'] : null;

	wp_enqueue_style(
		'theme-block-variations-styles',
		get_theme_file_uri( 'build/block-variations/index.css' ),
		array(),
		$version,
	);
	wp_enqueue_script(
		'theme-block-variations-scripts',
		get_theme_file_uri( 'build/block-variations/index.js' ),
		$dependencies,
		$version,
		false
	);

	// formats
	$asset        = include get_theme_file_path( 'build/formats/index.asset.php' );
	$dependencies = ( ! empty( $asset['dependencies'] ) ) ? $asset['dependencies'] : array();
	$version      = ( ! empty( $asset['version'] ) ) ? $asset['version'] : null;

	wp_enqueue_style(
		'theme-editor-styles',
		get_theme_file_uri( 'build/formats/index.css' ),
		array(),
		$version,
	);
	wp_enqueue_script(
		'theme-formats-scripts',
		get_theme_file_uri( 'build/formats/index.js' ),
		$dependencies,
		$version,
		false
	);
}
add_action( 'enqueue_block_assets', __NAMESPACE__ . '\\enqueue_editor_assets', 100 );


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
