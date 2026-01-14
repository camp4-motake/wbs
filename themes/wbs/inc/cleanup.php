<?php

/**
 * Cleanup
 *
 * @package wbs
 */

namespace Site\Theme\Cleanup;

/**
 * Remove Meta
 *
 * @return void
 */
function remove_head_meta() {
	remove_action( 'wp_head', 'wp_generator' );
	remove_action( 'wp_head', 'rsd_link' );
	remove_action( 'wp_head', 'wlwmanifest_link' );
	remove_action( 'wp_head', 'index_rel_link' );
	remove_action( 'wp_head', 'parent_post_rel_link', 10, 0 );
	remove_action( 'wp_head', 'start_post_rel_link', 10, 0 );
	remove_action( 'wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0 );
	remove_action( 'wp_head', 'wp_shortlink_wp_head', 10, 0 );
	remove_action( 'wp_head', 'feed_links', 2 );
	remove_action( 'wp_head', 'feed_links_extra', 3 );

	// Emoji.
	remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
	remove_action( 'admin_print_scripts', 'print_emoji_detection_script' );
	remove_action( 'wp_print_styles', 'print_emoji_styles' );
	remove_action( 'admin_print_styles', 'print_emoji_styles' );

	// Embed.
	remove_action( 'wp_head', 'rest_output_link_wp_head' );
	remove_action( 'wp_head', 'wp_oembeds_add_discovery_links' );
	remove_action( 'wp_head', 'wp_oembed_add_host_js' );
	remove_action( 'template_redirect', 'rest_output_link_header', 11 );

	// skip link
	// remove_action( 'wp_footer', 'the_block_template_skip_link' );
}
add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\\remove_head_meta', 102 );


/**
 * コメント削除
 */
add_filter( 'comments_open', '__return_false' );

/**
 * Remove recent_comments_style
 *
 * @return void
 */
function remove_recent_comments_style() {
	global $wp_widget_factory;
	remove_action(
		'wp_head',
		array(
			$wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
			'recent_comments_style',
		)
	);
}
add_action( 'widgets_init', __NAMESPACE__ . '\\remove_recent_comments_style' );

/**
 * REST APIから "user" を削除
 *
 * @param mixed[] $endpoints WP_REST_API Endpoints.
 * @return $endpoints
 */
function remove_user_endpoint( $endpoints ) {
	if ( is_user_logged_in() ) {
		return $endpoints;
	}
	if ( isset( $endpoints['/wp/v2/users'] ) ) {
		unset( $endpoints['/wp/v2/users'] );
	}
	if ( isset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] ) ) {
		unset( $endpoints['/wp/v2/users/(?P<id>[\d]+)'] );
	}
	return $endpoints;
}
add_filter( 'rest_endpoints', __NAMESPACE__ . '\\remove_user_endpoint', 10, 1 );

/**
 * author アーカイブを無効化
 *
 * 著者のリライトルールを無効化
 * 著者リンクを無効化
 * 著者アーカイブページにアクセスされた場合に404エラーを返す
 *
 */
add_filter( 'author_rewrite_rules', '__return_empty_array' );
add_filter( 'author_link', '__return_false' );
function disable_author_archives() {
	if ( is_author() ) {
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
	}
}
add_action( 'template_redirect', __NAMESPACE__ . '\\disable_author_archives' );

/**
 * author アーカイブをwp-sitemap.xmlから削除
 */
function remove_author_archive_from_sitemap( $provider, $name ) {
	if ( 'users' === $name ) {
		return false;
	}
	return $provider;
}
add_filter( 'wp_sitemaps_add_provider', __NAMESPACE__ . '\\remove_author_archive_from_sitemap', 10, 2 );

/**
 * 検索無効化
 */
function search_redirect() {
	if ( is_search() ) {
		wp_safe_redirect( home_url() );
		exit;
	}
}
add_action( 'template_redirect', __NAMESPACE__ . '\\search_redirect' );
