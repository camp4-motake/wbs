<?php

/**
 * SIte Icon Hooks
 *
 * @package wbs
 */

namespace Site\Theme\SiteIcon;

use Site\Theme\Helper\Path;

/**
 * Remove original site icon
 *
 * @param array $meta_tags .
 * @return array
 */
function force_remove_site_icon( $meta_tags ) {
	$meta_tags = array();

	return $meta_tags;
}
add_filter( 'site_icon_meta_tags', __NAMESPACE__ . '\\force_remove_site_icon' );

/**
 * WP サイトアイコンのリダイレクト先をテーマ内の Favicon 画像にオーバーライド
 *
 * @param string $url .
 * @return string
 */
function inject_theme_site_icon( $url ) {
	$ico_file = 'static/meta/favicon.svg';

	if ( file_exists( get_theme_file_path( $ico_file ) ) ) {
		return get_theme_file_uri( $ico_file );
	}

	return $url;
}
add_filter( 'get_site_icon_url', __NAMESPACE__ . '\\inject_theme_site_icon' );

/**
 * Additional head assets.
 *
 * @return void
 */
function additional_head_assets() {

	// favicon.
	if ( IS_ENABLE_THEME_SITE_ICON ) {
		echo '<link rel="apple-touch-icon" sizes="180x180" href="' . esc_url( Path\cache_buster( '/static/meta/apple-touch-icon.png' ) ) . '">' . "\n";
		echo '<link rel="icon" type="image/svg+xml" href="' . esc_url( Path\cache_buster( '/static/meta/favicon.svg' ) ) . '">' . "\n";
		echo '<link rel="icon" type="image/png" sizes="144x144" href="' . esc_url( Path\cache_buster( '/static/meta/favicon-144x144.png' ) ) . '">' . "\n";
		echo '<link rel="icon" type="image/png" sizes="48x48" href="' . esc_url( Path\cache_buster( '/static/meta/favicon-48x48.png' ) ) . '">' . "\n";
	}

	// ex) theme color.
	// echo '<meta name="theme-color" content="#468bb7">' . "\n";

	// ex) theme color (dark mode support)
	// echo '<meta name="theme-color" media="(prefers-color-scheme: light)" content="#efefef">' . "\n";
	// echo '<meta name="theme-color" media="(prefers-color-scheme: dark)" content="#323232">' . "\n";
}
add_action( 'wp_head', __NAMESPACE__ . '\\additional_head_assets', 3 );
