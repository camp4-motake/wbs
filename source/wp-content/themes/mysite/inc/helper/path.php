<?php

/**
 * Path Helper
 *
 * @package wbs
 */

namespace Site\Theme\Helper\Path;

/**
 * Cache buster
 *
 * @param string $path .
 * @return string
 */
function cache_buster( $path = '' ) {
	if ( ! $path ) {
		return '';
	}
	$asset_path = get_theme_file_path( $path );
	$asset_uri  = get_theme_file_uri( $path );
	$hash_id    = file_exists( $asset_path ) ? '?id=' . hash_file( 'fnv132', $asset_path ) : '';

	return $asset_uri . $hash_id;
}

/**
 * Theme Asset path
 *
 * @param string  $filepath .
 * @param boolean $cash_buster .
 * @return string
 */
function assets_uri( $filepath = '', $cash_buster = true ) {
	if ( $cash_buster ) {
		return get_theme_file_uri( cache_buster( 'build/' . $filepath ) );
	}
	return get_theme_file_uri( 'build/' . $filepath );
}
