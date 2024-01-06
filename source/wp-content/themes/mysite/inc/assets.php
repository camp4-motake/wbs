<?php

namespace MySite\Theme\Assets;

/**
 * メインアセット
 *
 * @return void
 */
function my_theme_assets() {

	$asset_meta = include_once get_theme_file_path( 'build/index.asset.php' );
	$version    = isset( $asset_meta['version'] ) ? $asset_meta['version'] : null;

	wp_enqueue_style(
		'mysite-style',
		get_theme_file_uri( '/build/index.css' ),
		array(),
		$version
	);

	wp_enqueue_script(
		'mysite-script',
		get_theme_file_uri( 'build/index.js' ),
		array(),
		$version
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\my_theme_assets' );


/**
 * wp_enqueue_script の出力を modules の script タグに置換
 */
function replace_module_script_tag( string $tag, string $handle, string $src ) {
	if ( in_array( $handle, array( 'mysite-script', 'vite' ), true ) ) {
		return '<script type="module" src="' . esc_url( $src ) . '" defer></script>' . "\n";
	}
	return $tag;
}
add_filter( 'script_loader_tag', __NAMESPACE__ . '\replace_module_script_tag', 10, 3 );
