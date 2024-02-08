<?php

/**
 * Utility
 *
 * @package wbs
 */

namespace Site\Theme\Helper\Util;

/**
 * Namespace 生成
 *
 * @param object $post .
 * @return string
 */
function get_namespace( $post ) {
	if ( is_front_page() ) {
		return 'home';
	}
	if ( is_page() ) {
		global $post;
		return $post->post_name ? $post->post_name : '';
	}
	return get_post_type( $post ) ? esc_html( get_post_type( $post ) ) : 'other';
}

/**
 * 親ページスラッグを取得
 *
 * @return string slug
 */
function get_parent_slug() {
	global $post;

	if ( isset( $post->post_parent ) ) {
		$post_data = get_post( $post->post_parent );
		return $post_data->post_name;
	}
}

/**
 * Share link 取得
 */
function get_share_links() {
	$title       = rawurldecode( get_the_title() );
	$current_url = rawurldecode( get_the_permalink() );

	return array(
		'x'        => "http://x.com/share?url={$current_url}&text={$title}",
		'twitter'  => "http://twitter.com/share?url={$current_url}&text={$title}",
		'facebook' => "https://www.facebook.com/sharer/sharer.php?href={$current_url}",
		'hatena'   => "http://b.hatena.ne.jp/add?mode=confirm&url={$current_url}&title={$title}",
		'line'     => "http://line.me/R/msg/text/?{$current_url}",
	);
}


/**
 * Blank Check
 *
 * @param [type]  $parm .
 * @param boolean $alt .
 * @return mixture
 */
function set_check( $parm = null, $alt = false ) {
	return isset( $parm ) ? $parm : $alt;
}

/**
 * URL文字列をリンクに変換
 * https://qiita.com/sukobuto/items/b6cdfa966b29823c62f0
 *
 * @param string $body .
 * @param string $link_title .
 * @return string
 */
function url2link( $body = '', $link_title = null ) {
	$pattern   = '/(?<!href=")https?:\/\/[-_.!~*\'()a-zA-Z0-9;\/?:@&=+$,%#]+/';
	$http_host = ! empty( $_SERVER['HTTP_HOST'] ) ? esc_url_raw( $_SERVER['HTTP_HOST'] ) : get_site_url();

	$body = preg_replace_callback(
		$pattern,
		function ( $matches ) use ( $link_title, $http_host ) {
			$link_title = $link_title ? $link_title : $matches[0];
			$link       = esc_url( $matches[0] );
			$target     = '';
			if ( isset( $http_host ) && strpos( $matches[0], $http_host ) === false ) {
				$target = ' target="_blank" rel="noopener"';
			}
			return "<a href=\"{$link}\"{$target}>$link_title</a>";
		},
		$body
	);

	return $body;
}

/**
 * Get iframe src URL
 *
 * @param string $html .
 * @return array preg_match
 */
function extract_src_url( $html = '' ) {
	$matches = false;
	if ( $html ) {
		preg_match( '/<*src *= *["\']?([^"\']*)/i', $html, $matches );
	}
	return $matches;
}

/**
 * Yoast Seo のメインタームを取得
 *
 * @param [type] $term_list .
 * @param [type] $post .
 * @return $term
 */
function get_main_term( $term_list, $post = null ) {
	if ( empty( $term_list ) ) {
		return null;
	}

	if ( ! $post ) {
		global $post;
	}

	$main_term = null;

	foreach ( $term_list as $term ) {
		if ( get_post_meta( $post->ID, '_yoast_wpseo_primary_category', true ) === $term->term_id ) {
			$main_term = $term;
		}
	}

	if ( ! $main_term ) {
		$main_term = $term_list[0];
	}

	return $main_term;
}

/**
 * YouTubeのIDを正規表現で抽出
 *
 * @param [type] $url .
 * @return string
 */
function get_youtube_id_from_url( $url = '' ) {
	preg_match(
		'/(http(s|):|)\/\/(www\.|)yout(.*?)\/(embed\/|watch.*?v=|)([a-z_A-Z0-9\-]{11})/i',
		$url,
		$results
	);

	return $results[6];
}

/**
 * Random text
 *
 * @param string  $str .
 * @param integer $min .
 * @param integer $max .
 * @return string
 */
function random_text( string $str = '', int $min = 0, int $max = 10 ): string {
	$text   = $str;
	$length = wp_rand( $min, $max );

	for ( $i = $min; $i < $length; $i++ ) {
		$text .= $str;
	}

	return $text;
}

/**
 * プラグインのアクティブ状態判定
 *
 * @param string $plugin_path .
 * @return boolean
 */
function plugin_exists( $plugin_path = '' ) {
	return ( in_array( $plugin_path, apply_filters( 'active_plugins', get_option( 'active_plugins' ) ), true ) );
}
