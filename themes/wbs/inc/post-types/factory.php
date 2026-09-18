<?php

/**
 * カスタム投稿タイプ ファクトリ
 *
 * 各 inc/post-types/*.php が宣言する config をもとに、投稿タイプ・タクソノミーの登録、
 * タクソノミーアーカイブのリライトルール追加、管理画面一覧の絞り込みドロップダウン表示を行う。
 *
 * Config スキーマ:
 * array(
 *   'post_type'      => string  (必須) 投稿タイプ名。rewrite slug のデフォルトを兼ねる
 *   'label'          => string  (必須) esc_html__ 済みの名称。labels 自動生成の素
 *   'menu_icon'      => string  (必須) dashicons-*
 *   'post_type_args' => array   (任意) register_post_type 引数への浅い上書き。
 *                               'labels' のみ自動生成分へ差分マージされる
 *   'taxonomies'     => array   (必須) タクソノミー名 => array(
 *     'label'           => string (必須) esc_html__ 済みの名称
 *     'slug'            => string (必須) rewrite slug
 *     'show_option_all' => string (任意) 絞り込みドロップダウンの全件ラベル
 *     'taxonomy_args'   => array  (任意) register_taxonomy 引数への浅い上書き。
 *                                 'labels' のみ自動生成分へ差分マージされる
 *   )
 * )
 *
 * @package wbs
 */

namespace Site\Theme\CustomPostType\Factory;

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
 * 投稿タイプ labels 生成
 *
 * @param string $label 投稿タイプの名称(esc_html__ 済み)。
 * @return array
 */
function get_post_type_labels( $label ) {
	return array(
		'name'               => $label,
		'singular_name'      => $label,
		'menu_name'          => $label,
		/* translators: %s: 投稿タイプの名称 */
		'add_new'            => sprintf( esc_html__( '%sを追加', 'wbs' ), $label ),
		/* translators: %s: 投稿タイプの名称 */
		'add_new_item'       => sprintf( esc_html__( '%sを新規追加', 'wbs' ), $label ),
		/* translators: %s: 投稿タイプの名称 */
		'edit_item'          => sprintf( esc_html__( '%sを編集する', 'wbs' ), $label ),
		/* translators: %s: 投稿タイプの名称 */
		'new_item'           => sprintf( esc_html__( '新規%s', 'wbs' ), $label ),
		/* translators: %s: 投稿タイプの名称 */
		'all_items'          => sprintf( esc_html__( '%s一覧', 'wbs' ), $label ),
		'view_item'          => esc_html__( '投稿を見る', 'wbs' ),
		'search_items'       => esc_html__( '検索する', 'wbs' ),
		/* translators: %s: 投稿タイプの名称 */
		'not_found'          => sprintf( esc_html__( '%sが見つかりませんでした。', 'wbs' ), $label ),
		/* translators: %s: 投稿タイプの名称 */
		'not_found_in_trash' => sprintf( esc_html__( 'ゴミ箱内に%sが見つかりませんでした。', 'wbs' ), $label ),
	);
}

/**
 * タクソノミー labels 生成
 *
 * @param string $label タクソノミーの名称(esc_html__ 済み)。
 * @return array
 */
function get_taxonomy_labels( $label ) {
	return array(
		'name'              => $label,
		'singular_name'     => $label,
		'menu_name'         => $label,
		/* translators: %s: タクソノミーの名称 */
		'search_items'      => sprintf( esc_html__( '%sを検索', 'wbs' ), $label ),
		/* translators: %s: タクソノミーの名称 */
		'all_items'         => sprintf( esc_html__( '全ての%s', 'wbs' ), $label ),
		/* translators: %s: タクソノミーの名称 */
		'parent_item'       => sprintf( esc_html__( '親%s', 'wbs' ), $label ),
		/* translators: %s: タクソノミーの名称 */
		'parent_item_colon' => sprintf( esc_html__( '親%s:', 'wbs' ), $label ),
		/* translators: %s: タクソノミーの名称 */
		'edit_item'         => sprintf( esc_html__( '%sを編集', 'wbs' ), $label ),
		/* translators: %s: タクソノミーの名称 */
		'update_item'       => sprintf( esc_html__( '%sを更新', 'wbs' ), $label ),
		/* translators: %s: タクソノミーの名称 */
		'add_new_item'      => sprintf( esc_html__( '%sを追加', 'wbs' ), $label ),
		/* translators: %s: タクソノミーの名称 */
		'new_item_name'     => sprintf( esc_html__( '新規%s', 'wbs' ), $label ),
	);
}

/**
 * 全 config の投稿タイプ・タクソノミーを登録
 */
function register_all() {
	foreach ( get_configs() as $config ) {
		set_post_type( $config );
		set_taxonomies( $config );
	}
}
add_action( 'init', __NAMESPACE__ . '\\register_all' );

/**
 * カスタム投稿タイプ登録
 * @see https://developer.wordpress.org/reference/functions/register_post_type/
 *
 * @param array $config 投稿タイプ config。
 */
function set_post_type( $config ) {
	$args = array(
		'labels'        => get_post_type_labels( $config['label'] ),
		'public'        => true,
		'has_archive'   => true,
		'show_in_rest'  => true,
		'show_ui'       => true,
		'show_in_menu'  => true,
		'supports'      => array( 'title', 'author', 'editor', 'excerpt', 'thumbnail', 'custom-fields', 'revisions' ),
		'rewrite'       => array( 'slug' => $config['post_type'] ),
		'menu_position' => 0,
		'menu_icon'     => $config['menu_icon'],
	);

	if ( isset( $config['post_type_args'] ) ) {
		$overrides = $config['post_type_args'];

		// labels だけは生成済みの一式へ差分マージする(menu_name だけ差し替える
		// といった用途で、他のラベルを巻き添えに消さないため)。
		if ( isset( $overrides['labels'] ) ) {
			$args['labels'] = array_merge( $args['labels'], $overrides['labels'] );
			unset( $overrides['labels'] );
		}

		$args = array_merge( $args, $overrides );
	}

	register_post_type( $config['post_type'], $args );
}

/**
 * カスタムタクソノミー登録
 * @see https://developer.wordpress.org/reference/functions/register_taxonomy/
 *
 * @param array $config 投稿タイプ config。
 */
function set_taxonomies( $config ) {
	foreach ( $config['taxonomies'] as $taxonomy => $tax_config ) {
		$args = array(
			'public'       => true,
			'show_ui'      => true,
			'hierarchical' => true,
			'show_in_rest' => true,
			'labels'       => get_taxonomy_labels( $tax_config['label'] ),
			'rewrite'      => array(
				'slug'         => $tax_config['slug'],
				'with_front'   => false,
				'hierarchical' => true,
			),
		);

		if ( isset( $tax_config['taxonomy_args'] ) ) {
			$tax_overrides = $tax_config['taxonomy_args'];

			if ( isset( $tax_overrides['labels'] ) ) {
				$args['labels'] = array_merge( $args['labels'], $tax_overrides['labels'] );
				unset( $tax_overrides['labels'] );
			}

			$args = array_merge( $args, $tax_overrides );
		}

		register_taxonomy( $taxonomy, array( $config['post_type'] ), $args );
	}
}

/**
 * カスタムタクソノミーのリライトルールを追加(アーカイブ + ページネーション)
 *
 * @param array $rules 既存のリライトルール。
 * @return array
 */
function set_taxonomy_rewrite_rules( $rules ) {
	$new_rules = array();

	foreach ( get_configs() as $config ) {
		foreach ( $config['taxonomies'] as $taxonomy => $tax_config ) {
			$new_rules[ $tax_config['slug'] . '/([^/]+)/?$' ]                   = 'index.php?' . $taxonomy . '=$matches[1]';
			$new_rules[ $tax_config['slug'] . '/([^/]+)/page/?([0-9]{1,})/?$' ] = 'index.php?' . $taxonomy . '=$matches[1]&paged=$matches[2]';
		}
	}

	return $new_rules + $rules;
}
add_filter( 'rewrite_rules_array', __NAMESPACE__ . '\\set_taxonomy_rewrite_rules' );

/**
 * 管理画面 投稿リストにカスタムタクソノミー絞り込みドロップダウンを追加
 *
 * @param string $current_post_type 表示中の投稿タイプ。
 */
function set_custom_filter_dropdown( $current_post_type ) {
	foreach ( get_configs() as $config ) {
		if ( $config['post_type'] !== $current_post_type ) {
			continue;
		}

		foreach ( $config['taxonomies'] as $taxonomy => $tax_config ) {
			wp_dropdown_categories(
				array(
					'show_option_all' => isset( $tax_config['show_option_all'] )
						? $tax_config['show_option_all']
						: esc_html__( 'すべてのカテゴリー', 'wbs' ),
					'orderby'         => 'name',
					'selected'        => get_query_var( $taxonomy ),
					'hide_empty'      => true,
					'name'            => $taxonomy,
					'taxonomy'        => $taxonomy,
					'value_field'     => 'slug',
				)
			);
		}
	}
}
add_action( 'restrict_manage_posts', __NAMESPACE__ . '\\set_custom_filter_dropdown' );
