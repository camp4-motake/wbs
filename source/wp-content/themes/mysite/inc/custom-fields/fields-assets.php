<?php

namespace Site\Theme\CustomFields\Register;

/**
 * カスタムフィールド登録
 */

/**
 * ブロックエディターへのカスタムパネル追加用アセットキュー
 */
function enqueue_custom_sidebar_plugin() {
	$asset_file = include get_theme_file_path( 'build/custom-fields/index.asset.php' );

	wp_enqueue_script(
		'custom-fields-common-scripts',
		get_theme_file_uri( 'build/custom-fields/index.js' ),
		$asset_file['dependencies'],
		$asset_file['version'],
		false
	);
}
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\enqueue_custom_sidebar_plugin' );
