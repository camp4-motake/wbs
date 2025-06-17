<?php

namespace Site\Theme\Kses;

/**
 * wp_kses_allowed_html に除外対象を追加
 *
 * @param [type] $tags
 * @return void
 */
function custom_kses_allowed_tags( $tags ) {
	$tags['wbr'] = array(); // wbr タグを許可
	return $tags;
}
add_filter( 'wp_kses_allowed_html', __NAMESPACE__ . '\\custom_kses_allowed_tags' );
