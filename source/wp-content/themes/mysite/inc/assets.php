<?php

/**
 * Theme view Assets
 *
 * @package wbs
 */

namespace Site\Theme\Assets;

/**
 * main theme assets
 */
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

/**
 * Block Styles / Variations
 */
function enqueue_theme_block_styles() {

	$asset = include get_theme_file_path( 'build/block-styles/index.asset.php' );

	wp_enqueue_style(
		'theme-block-styles',
		get_theme_file_uri( 'build/block-styles/index.css' ),
		array(),
		$asset['version']
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_theme_block_styles', 99 );
add_action( 'enqueue_block_assets', __NAMESPACE__ . '\\enqueue_theme_block_styles', 100 );
