<?php

/**
 * Plugin Hooks: ACF Pro
 *
 * @package wbs
 */

namespace Site\Theme\Plugins\Acf;

/**
 * プロダクション環境で ACF Editor を無効化
 *
 * @see https://www.advancedcustomfields.com/resources/how-to-hide-acf-menu-from-clients/#hide-on-live-site-only
 */
function init_plugin_acf_settings() {
	$disabled_env = array( 'production', 'staging' );
	$is_hidden    = in_array( wp_get_environment_type(), $disabled_env, true );

	if ( $is_hidden ) {
		add_filter( 'acf/settings/show_admin', '__return_false' );
	}
}
add_action( 'init', __NAMESPACE__ . '\\init_plugin_acf_settings' );


/**
 * acf/the_field/escape_html_optin
 * @see https://www.advancedcustomfields.com/blog/acf-6-2-5-security-release/#enable-the-new-behavior-early
 */
add_filter( 'acf/the_field/escape_html_optin', '__return_true' );

/**
 * ACF TinyMCE エディタ設定カスタマイズ
 */
function custom_tiny_mce_config( $toolbars ) {

	// TinyMCE エディタにフォントサイズ設定用のドロップダウン追加
	$key = array_search( 'fontsizeselect', $toolbars['Full'][2], true );
	if ( true !== $key ) {
		array_push( $toolbars['Full'][2], 'fontsizeselect' );
	}

	return $toolbars;
}
add_filter( 'acf/fields/wysiwyg/toolbars', __NAMESPACE__ . '\\custom_tiny_mce_config' );

/**
 * フォントサイズ変更
 *
 * @param array $init_array
 * @return array
 */
function custom_tiny_mce_font_sizes( $init_array ) {

	// フォントサイズ変更
	$init_array['fontsize_formats'] = '0.625rem 0.75rem 0.875rem 1em 1.125rem 1.25rem 1.5rem 1.75rem 2.5rem 4rem';

	return $init_array;
}
add_filter( 'tiny_mce_before_init', __NAMESPACE__ . '\\custom_tiny_mce_font_sizes' );

/**
 * WORKAROUND: ACFのデータが preview で反映されない現象対策
 * 参考: https://gist.github.com/ChrisLTD/892eccf385752dadaf5f
 */
add_filter(
	'_wp_post_revision_fields',
	function ( $fields ) {
		$fields['debug_preview'] = 'debug_preview';
		return $fields;
	}
);

add_action(
	'edit_form_after_title',
	function () {
		echo '<input type="hidden" name="debug_preview" value="debug_preview">';
	}
);


/**
 * ヘルパー: Linkフィールド配列からaタグの属性文字列を生成
 *
 * @param [array] $link
 * @return string
 */
function make_acf_link_attr_string( $link = array() ) {

	if ( empty( $link ) ) {
		return false;
	}

	$link_attr = 'href="' . esc_url( $link['url'] ) . '" ';

	if ( isset( $link['title'] ) ) {
		$link_attr .= ' title="' . esc_attr( $link['title'] ) . '"';
	}

	if ( isset( $link['target'] ) ) {
		$link_attr .= ' target="' . esc_attr( $link['target'] ) . '"';

		if ( '_blank' === $link['target'] ) {
			$link_attr .= ' rel="noopener noreferrer"';
		}
	}

	return $link_attr;
}
