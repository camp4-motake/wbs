<?php

/**
 * Head hook
 *
 * @package wbs
 */

namespace Site\Theme\Meta;

/**
 * remove meta viewport
 *
 * @see https://core.trac.wordpress.org/ticket/59510#comment:2
 */
function remove_meta_viewport_hook() {
	remove_action( 'wp_head', '_block_template_viewport_meta_tag', 0 );
}
add_action( 'pre_get_posts', __NAMESPACE__ . '\\remove_meta_viewport_hook' );

/**
 * add head meta
 */
function add_meta_viewport() {
	echo '<meta name="viewport" content="width=device-width, initial-scale=1, viewport-fit=cover" />' . "\n";
	echo '<meta name="format-detection" content="telephone=no" />' . "\n";
}
add_action( 'wp_head', __NAMESPACE__ . '\\add_meta_viewport', 0 );
