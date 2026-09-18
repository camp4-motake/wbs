<?php

/**
 * カスタムフィールド ファクトリ
 *
 * 各 inc/custom-fields/meta-*.php が宣言する config をもとに、投稿メタの登録と
 * エディタ用サイドバースクリプトの読み込みを行う。
 *
 * Config スキーマ:
 * array(
 *   'post_types'    => string[] (必須) 対象投稿タイプ名の配列
 *   'meta'          => array    (必須) メタキー => register_post_meta 引数の連想配列
 *   'editor_script' => array    (任意) エディタ用サイドバースクリプト
 *     'handle' => string (必須) wp_enqueue_script ハンドル名
 *     'path'   => string (必須) build 配下のディレクトリ(例 'custom-fields/news')。
 *                        build/<path>/index.js と index.asset.php を読み込む
 * )
 *
 * @package wbs
 */

namespace Site\Theme\CustomFields\Factory;

/**
 * Config callback レジストリ
 *
 * @param callable|null $config_callback 追加する callback。null なら取得のみ。
 * @return callable[]
 */
function config_callbacks( $config_callback = null ) {
	static $callbacks = array();

	if ( null !== $config_callback ) {
		$callbacks[] = $config_callback;
	}

	return $callbacks;
}

/**
 * Config を返す callback を登録する
 *
 * @param callable $config_callback config 配列を返す callback。
 */
function register_config( $config_callback ) {
	config_callbacks( $config_callback );
}

/**
 * 解決済み config 一覧(初回アクセス時に callback を実行してキャッシュ)
 *
 * @return array[]
 */
function get_configs() {
	static $configs = null;

	if ( null === $configs ) {
		$configs = array();
		foreach ( config_callbacks() as $callback ) {
			$configs[] = $callback();
		}
	}

	return $configs;
}

/**
 * 投稿メタの auth_callback 共通実装(編集権限の有無を返す)
 *
 * @return callable
 */
function auth_edit_posts() {
	return function () {
		return current_user_can( 'edit_posts' );
	};
}

/**
 * Pickup 記事メタ(wbs_is_pickup_post)の共通定義
 *
 * @return array
 */
function pickup_post_meta() {
	return array(
		'type'          => 'boolean',
		'single'        => true,
		'default'       => false,
		'show_in_rest'  => true,
		'auth_callback' => auth_edit_posts(),
	);
}

/**
 * 外部リンクメタの共通定義(LinkControl とやり取りするオブジェクト)
 *
 * @return array
 */
function external_link_meta() {
	return array(
		'type'          => 'object',
		'single'        => true,
		'show_in_rest'  => array(
			'schema' => array(
				'type'       => 'object',
				'properties' => array(
					'url'    => array( 'type' => 'string' ),
					'title'  => array( 'type' => 'string' ),
					'target' => array( 'type' => 'string' ),
					'rel'    => array(
						'type'  => 'array',
						'items' => array( 'type' => 'string' ),
					),
				),
			),
		),
		'auth_callback' => auth_edit_posts(),
	);
}

/**
 * 指定したメタキーを登録している投稿タイプ一覧を返す
 *
 * @param string $meta_key 対象のメタキー。
 * @return string[] 投稿タイプ名の配列(重複排除済み)
 */
function get_post_types_for_meta( $meta_key ) {
	$post_types = array();

	foreach ( get_configs() as $config ) {
		if ( ! isset( $config['meta'][ $meta_key ] ) ) {
			continue;
		}

		foreach ( $config['post_types'] as $post_type ) {
			$post_types[ $post_type ] = true;
		}
	}

	return array_keys( $post_types );
}

/**
 * 全 config の投稿メタを登録
 */
function register_all() {
	foreach ( get_configs() as $config ) {
		foreach ( $config['post_types'] as $post_type ) {
			foreach ( $config['meta'] as $meta_key => $meta_args ) {
				register_post_meta( $post_type, $meta_key, $meta_args );
			}
		}
	}
}
add_action( 'init', __NAMESPACE__ . '\\register_all' );

/**
 * 編集中の投稿タイプに対応する config のエディタスクリプトを読み込む
 */
function enqueue_editor_assets() {
	$current_post_type = get_post_type();

	foreach ( get_configs() as $config ) {
		if ( empty( $config['editor_script'] ) ) {
			continue;
		}

		if ( ! in_array( $current_post_type, $config['post_types'], true ) ) {
			continue;
		}

		$script     = $config['editor_script'];
		$asset_file = include get_theme_file_path( 'build/' . $script['path'] . '/index.asset.php' );

		wp_enqueue_script(
			$script['handle'],
			get_theme_file_uri( 'build/' . $script['path'] . '/index.js' ),
			$asset_file['dependencies'],
			$asset_file['version'],
			false
		);
	}
}
add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\\enqueue_editor_assets' );
