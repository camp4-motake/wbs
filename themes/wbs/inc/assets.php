<?php

/**
 * Theme view Assets
 *
 * @package wbs
 */

namespace Site\Theme\Assets;

/**
 * assets
 */
function enqueue_assets() {
	$is_admin = is_admin(); // "true" is admin only
	$prefix   = wp_get_theme()->get( 'TextDomain' );

	// assets
	$assets = array(
		'block-styles'     => array(
			'path'   => 'build/block-styles/register-styles',
			'script' => $is_admin,
			'style'  => false,
		),
		'block-variations' => array(
			'path'   => 'build/block-variations',
			'script' => $is_admin,
			'style'  => false, // Enable when using variations
		),
		'formats'          => array(
			'path'   => 'build/formats',
			'script' => $is_admin,
			'style'  => true,
		),
		'main'             => array(
			'path'   => 'build/main',
			'script' => ! $is_admin,
			'style'  => ! $is_admin,
		),
		'editor'           => array(
			'path'   => 'build/editor',
			'script' => $is_admin,
			'style'  => $is_admin,
		),
	);

	foreach ( $assets as $key => $asset ) {
		$path = get_theme_file_path( $asset['path'] . '/index.asset.php' );

		if ( ! file_exists( $path ) ) {
			continue;
		}

		$ctx          = include_once $path;
		$dependencies = ( ! empty( $ctx['dependencies'] ) ) ? $ctx['dependencies'] : array();
		$version      = ( ! empty( $ctx['version'] ) ) ? $ctx['version'] : null;

		if ( $asset['style'] && file_exists( get_theme_file_path( $asset['path'] . '/index.css' ) ) ) {
			$deps = $is_admin ? array() : array( 'global-styles' );
			wp_enqueue_style(
				"$prefix-theme-$key-styles",
				get_theme_file_uri( $asset['path'] . '/index.css' ),
				$deps,
				$version,
			);
		}

		if ( $asset['script'] && file_exists( get_theme_file_path( $asset['path'] . '/index.js' ) ) ) {
			wp_enqueue_script(
				"$prefix-theme-$key-scripts",
				get_theme_file_uri( $asset['path'] . '/index.js' ),
				$dependencies,
				$version,
				false
			);
		}
	}
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\enqueue_assets', 200 );
add_action( 'enqueue_block_assets', __NAMESPACE__ . '\\enqueue_assets', 200 );


/**
 * Enqueue block styles
 *
 * @return void
 */
function enqueue_block_styles() {

	$prefix = wp_get_theme()->get( 'TextDomain' );

	// target blocks
	$styles = array(
		'accordion' => array(
			'blocks' => 'core/accordion',
			'path'   => 'build/block-styles/accordion',
		),
		'buttons'   => array(
			'blocks' => 'core/buttons',
			'path'   => 'build/block-styles/buttons',
		),
		'image'     => array(
			'blocks' => 'core/image',
			'path'   => 'build/block-styles/image',
		),
	);

	foreach ( $styles as $key => $style ) {
		$path = get_theme_file_path( $style['path'] . '/index.asset.php' );

		if ( ! file_exists( $path ) ) {
			continue;
		}

		$ctx          = include_once $path;
		$dependencies = ( ! empty( $ctx['dependencies'] ) ) ? $ctx['dependencies'] : array();
		$version      = ( ! empty( $ctx['version'] ) ) ? $ctx['version'] : null;

		if ( file_exists( get_theme_file_path( $style['path'] . '/index.css' ) ) ) {
			wp_enqueue_block_style(
				$style['blocks'],
				array(
					'handle' => "$prefix-theme-block-styles-$key",
					'src'    => get_theme_file_uri( $style['path'] . '/index.css' ),
					'path'   => get_theme_file_path( $style['path'] . '/index.css' ),
					'deps'   => $dependencies,
					'ver'    => $version,
				)
			);
		}
	}
}
add_action( 'init', __NAMESPACE__ . '\\enqueue_block_styles', 200 );
