<?php

/**
 * Goggle Fonts
 *
 * @package wbs
 */

namespace Site\Theme\GoogleFonts;

define( 'GOOGLE_FONTS', array( '' ) );

/**
 * Goggle Font キューを追加
 */
function enqueue_google_fonts() {
	if ( ! GOOGLE_FONTS || ! ( count( GOOGLE_FONTS ) > 0 ) ) {
		return;
	}

	foreach ( GOOGLE_FONTS as $i => $fonts ) {
		$num = '-' . $i;
		if ( 0 === $i ) {
			$num = '';
		}
		wp_enqueue_style( 'google-fonts' . $num, esc_url( $fonts ), array(), null ); // phpcs:ignore WordPress.WP.EnqueuedResourceParameters.MissingVersion
	}
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . "\\enqueue_google_fonts" );
add_action( 'enqueue_block_assets', __NAMESPACE__ . "\\enqueue_google_fonts" );

/**
 * resource hints に Goggle Font の preconnect を追加
 */
function add_google_fonts_resource_hints( $hints, $relation_type ) {
	if ( is_admin() || is_user_logged_in() ) {
		return $hints;
	}

	if ( 'dns-prefetch' === $relation_type ) {
		$hints = array_filter(
			$hints,
			function ( $val ) {
				return 'fonts.googleapis.com' !== $val ? true : false;
			}
		);
	}

	if ( 'preconnect' === $relation_type && GOOGLE_FONTS && ( count( GOOGLE_FONTS ) > 0 ) ) {
		$hints[] = 'https://fonts.googleapis.com';
		$hints[] = 'https://fonts.gstatic.com';
	}

	return $hints;
}
add_filter( 'wp_resource_hints', __NAMESPACE__ . '\\add_google_fonts_resource_hints', 10, 2 );
