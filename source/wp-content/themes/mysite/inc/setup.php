<?php

namespace Site\Theme\Setup;

function site_theme_setup() {
	add_theme_support( 'wp-block-styles' );
	add_editor_style( 'editor-style.css' );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\site_theme_setup' );
