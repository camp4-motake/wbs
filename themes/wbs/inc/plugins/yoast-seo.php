<?php

/**
 * Yoast SEO 動作カスタマイズ用 hook & helper
 *
 * Yoast SEO - Metadata API
 * https://developer.yoast.com/customization/apis/metadata-api/
 *
 * @package wbs
 */

namespace Site\Theme\Plugins\YoastSeo;

/**
 * yoast SEO ユーザーを削除
 */
function remove_yoast_seo_user() {
	remove_role( 'wpseo_manager' );
	remove_role( 'wpseo_editor' );
}
add_action( 'after_setup_theme', __NAMESPACE__ . '\\remove_yoast_seo_user' );

/**
 * 管理画面: Yoast SEO のメタボックスを最下部に移動
 */
function move_yoast_seo_metabox() {
	return 'low';
}
add_filter( 'wpseo_metabox_prio', __NAMESPACE__ . '\\move_yoast_seo_metabox' );

/**
 * Yoast SEO のソースコメント出力を削除
 */
add_filter( 'wpseo_debug_markers', '__return_false' );

add_filter( 'Yoast\WP\SEO\disable_table_of_content_block', '__return_true' );

/**
 * Yoast SEO の言語上書き
 */
function update_yoast_seo_langage() {
	return get_locale();
}
add_filter( 'wpseo_locale', __NAMESPACE__ . '\\update_yoast_seo_langage' );

/**
 *  Yoast SEO の右サイド広告を非表示
 */
function remove_yoast_seo_ad() {
	echo '
	<style>
	#sidebar.yoast-sidebar,.yoast-notification.notice.notice-warning.is-dismissible,.yoast_premium_upsell { display: none !important;
	</style>' . "\n";
}
add_action( 'admin_print_styles', __NAMESPACE__ . '\\remove_yoast_seo_ad' );

/**
 * Yoast SEO のダッシュボードウィジェットを削除
 *
 * @return void
 */
function remove_yoast_seo_dashboard_widget() {
	remove_meta_box( 'wpseo-dashboard-overview', 'dashboard', 'side' );
	remove_meta_box( 'wpseo-wincher-dashboard-overview', 'dashboard', 'side' );
}
add_action( 'wp_dashboard_setup', __NAMESPACE__ . '\\remove_yoast_seo_dashboard_widget' );

/**
 * Helper: yoast seo breadcrumb 出力 + パンくずの矢印をHTMLタグに置換
 * ※置換するには、管理画面パンくず設定で区切り文字に %arrow を指定
 *
 * https://yoast.com/help/implement-wordpress-seo-breadcrumbs/
 */
function get_yoast_seo_breadcrumb() {
	if ( ! function_exists( 'yoast_breadcrumb' ) ) {
		return '';
	}

	$arrow_html = '>';

	ob_start();
	yoast_breadcrumb( '<div class="breadcrumb">', '</div>' . "\n" );
	$breadcrumb = ob_get_contents();
	ob_end_clean();

	$breadcrumb = str_replace( '%arrow', $arrow_html, $breadcrumb );

	return $breadcrumb;
}
