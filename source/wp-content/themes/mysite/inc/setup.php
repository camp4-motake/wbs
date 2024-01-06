<?php

namespace MySite\Theme\Setup;

function theme_setup() {
	add_theme_support( 'wp-block-styles' );
	add_editor_style( 'editor-style.css' );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\theme_setup' );
