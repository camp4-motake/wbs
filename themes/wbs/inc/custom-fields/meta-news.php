<?php

/**
 * カスタムフィールド: news
 *
 * @package wbs
 */

namespace Site\Theme\CustomFields\Meta\News;

use Site\Theme\CustomFields\Factory;

/**
 * Config 定義(スキーマは factory.php 参照)
 *
 * @return array
 */
function get_config() {
	return array(
		'post_types'    => array( 'news' ),
		'meta'          => array(
			// リンク
			'wbs_external_link' => Factory\external_link_meta(),
		),
		'editor_script' => array(
			'handle' => 'custom-fields-panel-news-scripts',
			'path'   => 'custom-fields/news',
		),
	);
}
Factory\register_config( __NAMESPACE__ . '\\get_config' );
