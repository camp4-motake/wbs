<?php

/**
 * トラッキングコード (IS_ENABLE_GTM_TRACKING のフラグ有効時に出力)
 *
 * @package wbs
 */

namespace Site\Theme\Tracking;

use Site\Theme\Helper\Env;

/**
 * Tracking tag: head
 */
function head_tracking_tag() {
	if ( ! is_gtm_active() ) {
		return;
	}
	echo <<<EOM
	<!-- gtm tag -->
EOM;
}
add_action( 'wp_head', __NAMESPACE__ . '\\head_tracking_tag' );

/**
 * Tracking tag: body open
 */
function body_open_tracking_tag() {
	if ( ! is_gtm_active() ) {
		return;
	}
	echo <<<EOM
	<!-- noscript -->
EOM;
}
add_action( 'wp_body_open', __NAMESPACE__ . '\\body_open_tracking_tag' );

/**
 * GTM resource hints 追加
 *
 * @param array  $hints .
 * @param string $relation_type .
 * @return array
 */
function add_gtm_resource_hints( $hints, $relation_type ) {
	if ( ! is_admin() && 'dns-prefetch' === $relation_type && is_gtm_active() ) {
		$hints[] = 'https://www.googletagmanager.com';
	}
	return $hints;
}
add_filter( 'wp_resource_hints', __NAMESPACE__ . '\\add_gtm_resource_hints', 10, 2 );

/**
 * GTM 有効可否判定
 *
 * @return boolean
 */
function is_gtm_active() {
	return IS_ENABLE_GTM_TRACKING && Env\in_production() && ! is_user_logged_in();
}
