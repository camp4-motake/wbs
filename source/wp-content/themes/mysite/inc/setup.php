<?php

/**
 * Setup
 *
 * @package wbs
 */

namespace Site\Theme\Setup;

/**
 * Theme Support
 *
 * @see https://developer.wordpress.org/reference/functions/add_theme_support/
 */
function site_theme_setup() {
	add_theme_support( 'align-wide' );
	add_theme_support( 'block-template-parts' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'wp-block-styles' );
	add_editor_style( 'editor-style.css' );
	add_theme_custom_menu();
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\\site_theme_setup' );

/**
 * Custom menu
 *
 * @see https://developer.wordpress.org/reference/functions/register_nav_menus/
 */
function add_theme_custom_menu() {
	register_nav_menus(
		array(
			'nav_primary'     => __( 'nav primary', 'wbs' ),
			'nav_primary_sub' => __( 'nav primary sub', 'wbs' ),
		)
	);
}
