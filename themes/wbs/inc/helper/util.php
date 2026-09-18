<?php

/**
 * Utility
 *
 * @package wbs
 */

namespace Site\Theme\Helper\Util;

/**
 * get_template_partと同じロジックでテンプレートファイルの存在を確認する
 *
 * @param string $slug テンプレートスラッグ
 * @param string|null $name テンプレート名（オプション）
 * @return bool テンプレートが存在すれば true
 */
function check_template_part_exists( $slug, $name = null ) {
	// スラッグを正規化（先頭のスラッシュを削除）
	$slug = ltrim( $slug, '/' );

	// 検索するテンプレートファイル名の配列を作成
	$templates = array();

	if ( ! empty( $name ) ) {
		// {slug}-{name}.php を最初に検索
		$templates[] = "{$slug}-{$name}.php";
	}

	// {slug}.php を次に検索
	$templates[] = "{$slug}.php";

	// locate_template()でテンプレートを検索
	// 見つかった場合はパスを、見つからない場合は''を返す
	$located = locate_template( $templates, false );

	return ! empty( $located );
}

/**
 * partials/ 配下のテンプレート名として安全なスラッグを生成する
 *
 * 任意の "partials/" 接頭辞だけを許容し、残りが英小文字・数字・ハイフン・
 * アンダースコアのみで構成されることを検証する。スラッシュとドットを
 * 許可しないため .. や .php、ヌルバイトは弾かれ、ディレクトリ
 * トラバーサルを防止する。不正な値は黙って読み替えず空文字列を返す。
 *
 * @param string $value エディタで入力されたテンプレート名（partials/ 接頭辞は任意）
 * @return string 安全な場合は "partials/{name}"、不正な場合は空文字列
 */
function sanitize_partial_slug( string $value ): string {
	// Windows 由来の区切り文字を正規化し、許容する接頭辞のみ取り除く
	$name = str_replace( '\\', '/', $value );

	if ( str_starts_with( $name, 'partials/' ) ) {
		$name = substr( $name, strlen( 'partials/' ) );
	}

	// 英小文字・数字・ハイフン・アンダースコアのみ許可（/ や .. や .php、ヌルバイトを排除）
	if ( ! preg_match( '/\A[a-z0-9_-]+\z/', $name ) ) {
		return '';
	}

	return 'partials/' . $name;
}

/**
 * Namespace 生成
 *
 * @param object $post_object .
 * @return string
 */
function get_namespace( $post_object ) {
	if ( is_front_page() ) {
		return 'home';
	}
	if ( is_page() ) {
		global $post;
		return $post->post_name ? $post->post_name : '';
	}
	return get_post_type( $post_object ) ? esc_html( get_post_type( $post_object ) ) : 'other';
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
 * @param [type] $post_object .
 * @return $term
 */
function get_main_term( $term_list, $post_object = null ) {
	if ( empty( $term_list ) ) {
		return null;
	}

	if ( ! $post_object ) {
		global $post;
		$post_object = $post;
	}

	$main_term = null;

	foreach ( $term_list as $term ) {
		if ( get_post_meta( $post_object->ID, '_yoast_wpseo_primary_category', true ) === $term->term_id ) {
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

/**
 * 指定スラッグのページが公開されているか判定
 *
 * @param [type] $slug
 * @return boolean
 */
function is_page_published_by_slug( $slug ) {
	$page = get_page_by_path( $slug );
	if ( $page ) {
		return get_post_status( $page->ID ) === 'publish';
	}
	return false;
}

/**
 * 投稿に紐づく公開タクソノミのターム一覧をフラットに取得
 *
 * 投稿タイプに登録された public なタクソノミ（例: case なら業種＋サービス）を
 * 横断し、その投稿に付与されたタームを 1 本の配列にまとめて返す。
 *
 * @param int|\WP_Post|null $post_object 対象投稿（省略時はループ内の現在の投稿）。
 * @return \WP_Term[] タームの配列（無ければ空配列）。
 */
function get_post_term_list( $post_object = null ) {
	$post_object = get_post( $post_object );
	if ( ! $post_object ) {
		return array();
	}

	$taxonomies = get_object_taxonomies( $post_object->post_type, 'objects' );
	$terms      = array();

	foreach ( $taxonomies as $taxonomy ) {
		if ( empty( $taxonomy->public ) ) {
			continue;
		}

		$tax_terms = get_the_terms( $post_object, $taxonomy->name );
		if ( is_wp_error( $tax_terms ) || empty( $tax_terms ) ) {
			continue;
		}

		$terms = array_merge( $terms, $tax_terms );
	}

	return $terms;
}

/**
 * 投稿のリンク先属性を解決する
 *
 * カスタムフィールド wbs_external_link が設定されていればその外部リンク
 * （target / rel、新しいタブで開く場合は noopener を付与）を返し、未設定なら
 * パーマリンクを返す。content.php / content-bar-link.php などのカードリンクで共用する。
 *
 * @param int|\WP_Post|null $post_object 対象投稿（省略時はループ内の現在の投稿）。
 * @return array{url:string,new_tab:bool,rel:string} url=リンク先、new_tab=新しいタブで開くか、rel=スペース区切りの rel 値。
 */
function get_post_link_attributes( $post_object = null ) {
	$post_object = get_post( $post_object );
	if ( ! $post_object ) {
		return array(
			'url'     => '',
			'new_tab' => false,
			'rel'     => '',
		);
	}

	$external_link = get_post_meta( $post_object->ID, 'wbs_external_link', true );
	$external_url  = ( is_array( $external_link ) && ! empty( $external_link['url'] ) ) ? $external_link['url'] : '';

	$new_tab = $external_url && ! empty( $external_link['target'] ) && '_blank' === $external_link['target'];
	$rel     = '';

	if ( $external_url ) {
		$rel_values = ( ! empty( $external_link['rel'] ) && is_array( $external_link['rel'] ) ) ? $external_link['rel'] : array();
		if ( $new_tab && ! in_array( 'noopener', $rel_values, true ) ) {
			$rel_values[] = 'noopener';
		}
		$rel = implode( ' ', $rel_values );
	}

	return array(
		'url'     => $external_url ? $external_url : get_permalink( $post_object ),
		'new_tab' => $new_tab,
		'rel'     => $rel,
	);
}
