<?php

/**
 * Theme Setup
 *
 * @package wbs
 */

namespace Site\Theme\Setup;

function site_theme_setup() {
	/** Theme Support @see https://developer.wordpress.org/reference/functions/add_theme_support/ */
	add_theme_support( 'title-tag' );
	add_theme_support( 'align-wide' );
	add_theme_support( 'block-template-parts' );
	add_theme_support( 'post-thumbnails' );
	add_theme_support( 'wp-block-styles' );
	add_editor_style( 'editor-style.css' );

	/** Custom menu @see https://developer.wordpress.org/reference/functions/register_nav_menus/ */
	register_nav_menus(
		array(
			'nav_header' => __( 'nav header', 'wbs' ),
			'nav_footer' => __( 'nav footer', 'wbs' ),
		)
	);
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\\site_theme_setup' );
