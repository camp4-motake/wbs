<?php

/**
 * Plugin Custom Hook: post-type-order
 *
 * @package wbs
 */

namespace Site\Theme\Plugins\PostTypeOrder;

/**
 *  post type order / taxonomy terms order の広告を非表示
 */
function remove_post_type_order_ad() {
	echo '<style>#cpt_info_box { display: none !important;</style>' . "\n";
}
add_action( 'admin_print_styles', __NAMESPACE__ . '\\remove_post_type_order_ad' );
