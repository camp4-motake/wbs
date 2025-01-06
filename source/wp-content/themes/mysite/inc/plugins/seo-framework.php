<?php

/**
 * The SEO Framework hooks
 *
 * @see https://theseoframework.com/docs/
 *
 * @package wbs
 */

namespace Site\Theme\Plugins\TheSEOFramework;

/**
 * metabox を最下部に移動
 */
function move_tsf_metabox_position_low() {
	if ( ! current_user_can( 'edit_pages' ) ) {
		return 99;
	}
	return 'low';
}
add_filter( 'the_seo_framework_metabox_priority', __NAMESPACE__ . '\\move_tsf_metabox_position_low' );

/**
 * plugin コメント削除
 * @see https://github.com/sybrew/The-SEO-Framework-Extension-Manager/blob/master/extensions/free/incognito/trunk/incognito.php
 */
// Removes the HTML indicator that's wrapped around the SEO HTML output.
add_filter( 'the_seo_framework_indicator', '__return_false' );

// Removes "Fixed" indicator from the Title Fix extension.
add_filter( 'the_seo_framework_title_fixed_indicator', '__return_false' );

// Removes "Generated by The SEO Framework" from the Sitemap output.
add_filter( 'the_seo_framework_indicator_sitemap', '__return_false' );

/**
 * 非公開ページを強制 noindex
 */
/* // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
function set_noindex_pages( $noindex ) { if ( is_author() ) { $noindex = false; } return $noindex; }
add_filter( 'the_seo_framework_enable_noindex_no_posts', __NAMESPACE__ . '\\set_noindex_pages' );
 */
