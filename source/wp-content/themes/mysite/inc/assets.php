<?php

/**
 * Theme Assets
 *
 * @package wbs
 */

namespace Site\Theme\Assets;

/**
 * Main assets
 */
function enqueue_theme_assets() {

	$asset = include_once get_theme_file_path( 'build/main/index.asset.php' );

	wp_enqueue_style(
		'theme-styles',
		get_theme_file_uri( 'build/main/index.css' ),
		array(),
		$asset['version']
	);

	wp_enqueue_script(
		'theme-scripts',
		get_theme_file_uri( 'build/main/index.js' ),
		array(),
		$asset['version'],
		false
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_theme_assets', 100 );

/**
 * replace module script tag
 */
function replace_module_script_tag( string $tag, string $handle, string $src ) {

	$targets = array( 'theme-scripts' );

	if ( in_array( $handle, $targets, true ) ) {
		return '<script type="module" src="' . esc_url( $src ) . '" defer></script>' . "\n"; // phpcs:ignore WordPress.WP.EnqueuedResources.NonEnqueuedScript
	}

	return $tag;
}
add_filter( 'script_loader_tag', __NAMESPACE__ . '\\replace_module_script_tag', 10, 3 );
