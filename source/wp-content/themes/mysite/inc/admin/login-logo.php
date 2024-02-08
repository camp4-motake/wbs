<?php

/**
 * Login logo customize
 *
 * @package wbs
 */

namespace Site\Theme\Admin\LoginLogo;

/**
 *  ログインページのロゴ変更.
 */
function add_logo_inline_style() {
	$logo = 'build/images/logo-brand.svg';

	if ( ! file_exists( get_theme_file_path( $logo ) ) ) {
		return;
	}

	$logo = get_theme_file_uri( $logo );

	echo '
	<style type="text/css">
		body.login #login h1 a {
			background-image: none, url(' . esc_url( $logo ) . ') !important;
			background-size: contain;
			width: 86%;
			height: 40px;
			margin-bottom: 0.5rem;
		}
	</style>';
}
add_action( 'login_enqueue_scripts', __NAMESPACE__ . '\\add_logo_inline_style' );

/**
 * ログインページロゴのリンク先を指定.
 */
function change_logo_link_url() {
	return get_bloginfo( 'url' );
}
add_filter( 'login_headerurl', __NAMESPACE__ . '\\change_logo_link_url' );

/**
 * ログインページロゴのtitle変更.
 */
function change_logo_title() {
	return get_option( 'blogname' );
}
add_filter( 'login_headertext', __NAMESPACE__ . '\\change_logo_title' );

/**
 * ログインページの言語切替を削除
 */
add_filter( 'login_display_language_dropdown', '__return_false' );
