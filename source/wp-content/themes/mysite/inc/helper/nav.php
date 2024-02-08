<?php

/**
 * custom menu helper
 *
 * @package wbs
 */

namespace Site\Theme\Helper\Nav;

function make_flat_menu_html( $menu_slug = 'nav-primary', $is_wrap_li = true ) {

	$nav_items = wp_get_nav_menu_items( $menu_slug );

	$html = '';

	if ( $nav_items ) {
		foreach ( $nav_items as $item ) {

			$aria_current = '';
			$is_current   = (
				wp_parse_url( $_SERVER['REQUEST_URI'], PHP_URL_PATH ) ===
				wp_parse_url( $item->url, PHP_URL_PATH )
			);

			if ( $is_current ) {
				$aria_current .= ' aria-current="page"';
			}

			if ( $is_wrap_li ) {
				$html .= '<li>';
			}

			$html .= '<a href="' . $item->url . '" target="' . $item->target . '"' . $aria_current . '>';

			if ( ! empty( $item->description ) ) {
				$html .= '<small>' . $item->description . '</small>';
			}

			$html .= '<span>' . $item->title . '</span>';
			$html .= '</a>';

			if ( $is_wrap_li ) {
				$html .= '</li>';
			}

			$html .= "\n";
		}
	}

	return $html;
}
