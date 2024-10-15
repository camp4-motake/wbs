<?php

/**
 * Theme Assets
 *
 * @package wbs
 */

namespace Site\Theme\Assets;

function enqueue_theme_assets() {

	$asset = include get_theme_file_path( 'build/main/index.asset.php' );

	wp_enqueue_style(
		'theme-styles',
		get_theme_file_uri( 'build/main/index.css' ),
		array(),
		$asset['version']
	);

	wp_enqueue_script_module(
		'theme-scripts',
		get_theme_file_uri( 'build/main/index.js' ),
		$asset['dependencies'],
		$asset['version'],
		false
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_theme_assets', 100 );
