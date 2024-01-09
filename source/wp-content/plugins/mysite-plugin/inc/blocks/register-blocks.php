<?php

namespace MySite\Plugin\Blocks;

/**
 * Registers the block using the metadata loaded from the `block.json` file.
 * Behind the scenes, it registers also all assets so they can be enqueued
 * through the block editor in the corresponding context.
 *
 * @see https://developer.wordpress.org/reference/functions/register_block_type/
 */
function block_init() {
	foreach ( glob( plugin_dir_path( __FILE__ ) . '../build/blocks/*' ) as $block ) {
		register_block_type( $block );
	}
}
add_action( 'init', __NAMESPACE__ . '\block_init' );
