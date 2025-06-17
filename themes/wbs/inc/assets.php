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
		'theme-main-styles',
		get_theme_file_uri( 'build/main/index.css' ),
		array(),
		$asset['version']
	);

	wp_enqueue_script_module(
		'theme-main-scripts',
		get_theme_file_uri( 'build/main/index.js' ),
		$asset['dependencies'],
		$asset['version'],
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\enqueue_theme_assets', 100 );

/**
 * Block Styles / Variations / Format assets
 */
function enqueue_theme_block_styles() {

	// Block Styles
	$asset = include get_theme_file_path( 'build/block-styles/index.asset.php' );
	wp_enqueue_style(
		'theme-block-styles',
		get_theme_file_uri( 'build/block-styles/index.css' ),
		array(),
		$asset['version']
	);

	// Block Variations
	$asset = include get_theme_file_path( 'build/block-variations/index.asset.php' );
	wp_enqueue_style(
		'theme-block-variations',
		get_theme_file_uri( 'build/block-variations/index.css' ),
		array(),
		$asset['version']
	);

	// Format api style
	$asset = include get_theme_file_path( 'build/formats/index.asset.php' );
	wp_enqueue_style(
		'theme-formats',
		get_theme_file_uri( 'build/formats/index.css' ),
		array(),
		$asset['version']
	);
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_theme_block_styles', 99 );
add_action( 'enqueue_block_assets', __NAMESPACE__ . '\\enqueue_theme_block_styles', 100 );

/**
 * register swiper/core common style
 * @see https://ja.wordpress.org/team/handbook/block-editor/reference-guides/block-api/block-metadata/#wpdefinedasset
 */
function enqueue_early_styles() {

	$asset = include get_theme_file_path( 'build/swiper/index.asset.php' );

	wp_register_style(
		'theme-swiper-styles',
		get_theme_file_uri( 'build/swiper/index.css' ),
		array(),
		$asset['version'],
	);
}
add_action( 'enqueue_block_assets', __NAMESPACE__ . '\enqueue_early_styles', -999 );
