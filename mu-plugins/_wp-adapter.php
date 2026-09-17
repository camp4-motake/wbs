<?php
/**
 * MCP Adapter — 動作確認用の公開設定（開発環境限定）。
 *
 * ⚠️ 試験的に設置しているファイルです。動作確認・検証目的の暫定的なもので、
 *    恒久的な機能ではなく今後予告なく削除・変更される可能性があります。
 *    本番環境には持ち込まず、ローカル(wp-env)での検証用途に限定して使用すること。
 *    詳細は docs/mcp-abilities.md を参照。
 *
 * 編集者(Editor)権限と同等の範囲を MCP 経由で操作可能にする:
 *   - 固定ページ(page) とカスタム投稿タイプ(_builtin=false)
 *   - それらに関連するタクソノミー
 * ビルトインの投稿(post)・添付ファイル等、および設定/ユーザー/プラグイン等の
 * 管理者専用領域は対象外。削除は「ゴミ箱へ移動」のみ（完全削除は提供しない）。
 *
 * 各 ability は permission_callback で投稿タイプ/タクソノミーのプリミティブ
 * ケーパビリティにゲートしているため、より低い権限のユーザーで認証した場合は
 * 編集者相当の cap を持たない限り実行できない。
 *
 * @package Site\MU\McpAdapter
 */

declare( strict_types=1 );

/**
 * 既存: core/get-site-info を MCP 公開する。
 *
 * コアの Abilities API に per-ability の meta フィルタは無いため、
 * 登録引数を差し替える `wp_register_ability_args` フィルタで meta.mcp.public を立てる。
 */
add_filter(
	'wp_register_ability_args',
	static function ( array $args, string $name ): array {
		if ( 'core/get-site-info' === $name ) {
			$args['meta']['mcp']['public'] = true;
		}
		return $args;
	},
	10,
	2
);

/**
 * 動作確認用の ability カテゴリを登録する。
 *
 * カテゴリは専用フック `wp_abilities_api_categories_init` でのみ登録可能
 * （`wp_abilities_api_init` より前に発火する）。mcp-adapter のカテゴリは
 * 本 mu-plugin より後に登録されるため流用できない。
 *
 * @return void
 */
function wbs_mcp_register_ability_category(): void {
	if ( ! function_exists( 'wp_register_ability_category' ) || wp_has_ability_category( 'wbs-mcp' ) ) {
		return;
	}

	wp_register_ability_category(
		'wbs-mcp',
		array(
			'label'       => __( 'WBS MCP (dev)', 'wbs' ),
			'description' => __( '動作確認用の編集者相当 ability。', 'wbs' ),
		)
	);
}
add_action( 'wp_abilities_api_categories_init', 'wbs_mcp_register_ability_category' );

/**
 * 操作対象として許可する投稿タイプ一覧を返す。
 *
 * 固定ページ + カスタム投稿タイプ（ビルトインは page のみ許可）。
 *
 * @return string[]
 */
function wbs_mcp_allowed_post_types(): array {
	$allowed = array();

	foreach ( get_post_types( array( 'show_in_rest' => true ), 'objects' ) as $obj ) {
		if ( 'page' === $obj->name ) {
			$allowed[] = $obj->name;
			continue;
		}

		if ( empty( $obj->_builtin ) ) {
			$allowed[] = $obj->name; // カスタム投稿タイプ。
		}
	}

	return $allowed;
}

/**
 * 操作対象として許可するタクソノミー一覧を返す。
 *
 * 許可された投稿タイプに紐づくタクソノミーのみ。
 *
 * @return string[]
 */
function wbs_mcp_allowed_taxonomies(): array {
	$taxonomies = array();

	foreach ( wbs_mcp_allowed_post_types() as $post_type ) {
		foreach ( get_object_taxonomies( $post_type ) as $taxonomy ) {
			$taxonomies[ $taxonomy ] = true;
		}
	}

	return array_keys( $taxonomies );
}

/**
 * 指定投稿タイプのプリミティブ cap を現在のユーザーが持つか判定する。
 *
 * @param string $post_type 投稿タイプ名。
 * @param string $primitive cap オブジェクトのプロパティ名（例: 'edit_posts'）。
 * @return bool
 */
function wbs_mcp_user_can_post_type( string $post_type, string $primitive ): bool {
	if ( ! in_array( $post_type, wbs_mcp_allowed_post_types(), true ) ) {
		return false;
	}

	$obj = get_post_type_object( $post_type );
	if ( ! $obj instanceof WP_Post_Type ) {
		return false;
	}

	$cap = $obj->cap->$primitive ?? '';

	return '' !== $cap && current_user_can( $cap );
}

/**
 * 指定タクソノミーのプリミティブ cap を現在のユーザーが持つか判定する。
 *
 * @param string $taxonomy  タクソノミー名。
 * @param string $primitive cap オブジェクトのプロパティ名（例: 'manage_terms'）。
 * @return bool
 */
function wbs_mcp_user_can_taxonomy( string $taxonomy, string $primitive ): bool {
	if ( ! in_array( $taxonomy, wbs_mcp_allowed_taxonomies(), true ) ) {
		return false;
	}

	$obj = get_taxonomy( $taxonomy );
	if ( ! $obj instanceof WP_Taxonomy ) {
		return false;
	}

	$cap = $obj->cap->$primitive ?? '';

	return '' !== $cap && current_user_can( $cap );
}

/**
 * ID から許可範囲内の投稿を取得する。範囲外・不存在は WP_Error。
 *
 * @param mixed $id 投稿 ID。
 * @return WP_Post|WP_Error
 */
function wbs_mcp_guarded_post( $id ) {
	$post = get_post( absint( $id ) );

	if ( ! $post instanceof WP_Post ) {
		return new WP_Error( 'wbs_mcp_not_found', __( '指定された投稿が見つかりません。', 'wbs' ) );
	}

	if ( ! in_array( $post->post_type, wbs_mcp_allowed_post_types(), true ) ) {
		return new WP_Error( 'wbs_mcp_forbidden_type', __( 'この投稿タイプは操作対象外です。', 'wbs' ) );
	}

	return $post;
}

/**
 * 投稿の要約データを配列で返す。
 *
 * @param WP_Post $post 投稿。
 * @return array<string, mixed>
 */
function wbs_mcp_post_summary( WP_Post $post ): array {
	return array(
		'id'       => (int) $post->ID,
		'title'    => get_the_title( $post ),
		'status'   => (string) $post->post_status,
		'type'     => (string) $post->post_type,
		'slug'     => (string) $post->post_name,
		'link'     => (string) get_permalink( $post ),
		'modified' => (string) $post->post_modified_gmt,
	);
}

/**
 * 入力の terms を許可タクソノミーに限定して投稿に割り当てる。
 *
 * @param WP_Post              $post  対象投稿。
 * @param array<string, mixed> $terms taxonomy => (int|int[]) のマップ。
 * @return void
 */
function wbs_mcp_assign_terms( WP_Post $post, array $terms ): void {
	$object_taxonomies = get_object_taxonomies( $post->post_type );

	foreach ( $terms as $taxonomy => $term_ids ) {
		if ( ! in_array( $taxonomy, $object_taxonomies, true ) ) {
			continue;
		}
		if ( ! wbs_mcp_user_can_taxonomy( (string) $taxonomy, 'assign_terms' ) ) {
			continue;
		}

		$ids = array_map( 'absint', (array) $term_ids );
		wp_set_object_terms( $post->ID, $ids, (string) $taxonomy );
	}
}

/**
 * 動作確認用の ability を登録する。
 *
 * @param WP_Abilities_Registry $registry レジストリ（未使用）。
 * @return void
 */
function wbs_mcp_register_abilities( $registry ): void {
	unset( $registry );

	if ( ! function_exists( 'wp_register_ability' ) ) {
		return;
	}

	$post_type_enum = wbs_mcp_allowed_post_types();
	$taxonomy_enum  = wbs_mcp_allowed_taxonomies();
	$status_enum    = array( 'any', 'publish', 'future', 'draft', 'pending', 'private' );

	$public_meta = static function ( array $extra = array() ): array {
		return array(
			'mcp'         => array(
				'public' => true,
				'type'   => 'tool',
			),
			'annotations' => $extra,
		);
	};

	// ---- コンテンツ（固定ページ / カスタム投稿タイプ）----

	wp_register_ability(
		'wbs/list-content',
		array(
			'label'               => 'List content',
			'description'         => '固定ページ・カスタム投稿タイプの一覧を取得する。',
			'category'            => 'wbs-mcp',
			'input_schema'        => array(
				'type'       => 'object',
				'properties' => array(
					'post_type' => array(
						'type' => 'string',
						'enum' => $post_type_enum,
					),
					'status'    => array(
						'type'    => 'string',
						'enum'    => $status_enum,
						'default' => 'any',
					),
					'search'    => array( 'type' => 'string' ),
					'per_page'  => array(
						'type'    => 'integer',
						'minimum' => 1,
						'maximum' => 100,
						'default' => 20,
					),
					'page'      => array(
						'type'    => 'integer',
						'minimum' => 1,
						'default' => 1,
					),
				),
				'required'   => array( 'post_type' ),
			),
			'permission_callback' => static function ( $input ): bool {
				return wbs_mcp_user_can_post_type( (string) ( $input['post_type'] ?? '' ), 'edit_posts' );
			},
			'execute_callback'    => 'wbs_mcp_execute_list_content',
			'meta'                => $public_meta( array( 'readonly' => true, 'destructive' => false, 'idempotent' => true ) ),
		)
	);

	wp_register_ability(
		'wbs/get-content',
		array(
			'label'               => 'Get content',
			'description'         => '固定ページ・カスタム投稿タイプの単一項目を取得する（本文・割り当てタームを含む）。',
			'category'            => 'wbs-mcp',
			'input_schema'        => array(
				'type'       => 'object',
				'properties' => array(
					'id' => array(
						'type'    => 'integer',
						'minimum' => 1,
					),
				),
				'required'   => array( 'id' ),
			),
			'permission_callback' => static function ( $input ): bool {
				$post = wbs_mcp_guarded_post( $input['id'] ?? 0 );
				if ( is_wp_error( $post ) ) {
					return false;
				}
				return wbs_mcp_user_can_post_type( $post->post_type, 'edit_posts' );
			},
			'execute_callback'    => 'wbs_mcp_execute_get_content',
			'meta'                => $public_meta( array( 'readonly' => true, 'destructive' => false, 'idempotent' => true ) ),
		)
	);

	wp_register_ability(
		'wbs/create-content',
		array(
			'label'               => 'Create content',
			'description'         => '固定ページ・カスタム投稿タイプを新規作成する。',
			'category'            => 'wbs-mcp',
			'input_schema'        => array(
				'type'       => 'object',
				'properties' => array(
					'post_type' => array(
						'type' => 'string',
						'enum' => $post_type_enum,
					),
					'title'     => array( 'type' => 'string' ),
					'content'   => array( 'type' => 'string' ),
					'excerpt'   => array( 'type' => 'string' ),
					'slug'      => array( 'type' => 'string' ),
					'status'    => array(
						'type'    => 'string',
						'enum'    => array( 'draft', 'pending', 'publish', 'private', 'future' ),
						'default' => 'draft',
					),
					'terms'     => array(
						'type'                 => 'object',
						'additionalProperties' => array(
							'type'  => 'array',
							'items' => array( 'type' => 'integer' ),
						),
					),
				),
				'required'   => array( 'post_type', 'title' ),
			),
			'permission_callback' => static function ( $input ): bool {
				$post_type = (string) ( $input['post_type'] ?? '' );
				if ( ! wbs_mcp_user_can_post_type( $post_type, 'create_posts' ) ) {
					return false;
				}
				$status = (string) ( $input['status'] ?? 'draft' );
				if ( in_array( $status, array( 'publish', 'future', 'private' ), true ) ) {
					return wbs_mcp_user_can_post_type( $post_type, 'publish_posts' );
				}
				return true;
			},
			'execute_callback'    => 'wbs_mcp_execute_create_content',
			'meta'                => $public_meta( array( 'readonly' => false, 'destructive' => false, 'idempotent' => false ) ),
		)
	);

	wp_register_ability(
		'wbs/update-content',
		array(
			'label'               => 'Update content',
			'description'         => '固定ページ・カスタム投稿タイプを更新する。',
			'category'            => 'wbs-mcp',
			'input_schema'        => array(
				'type'       => 'object',
				'properties' => array(
					'id'      => array(
						'type'    => 'integer',
						'minimum' => 1,
					),
					'title'   => array( 'type' => 'string' ),
					'content' => array( 'type' => 'string' ),
					'excerpt' => array( 'type' => 'string' ),
					'slug'    => array( 'type' => 'string' ),
					'status'  => array(
						'type' => 'string',
						'enum' => array( 'draft', 'pending', 'publish', 'private', 'future' ),
					),
					'terms'   => array(
						'type'                 => 'object',
						'additionalProperties' => array(
							'type'  => 'array',
							'items' => array( 'type' => 'integer' ),
						),
					),
				),
				'required'   => array( 'id' ),
			),
			'permission_callback' => static function ( $input ): bool {
				$post = wbs_mcp_guarded_post( $input['id'] ?? 0 );
				if ( is_wp_error( $post ) ) {
					return false;
				}
				if ( ! wbs_mcp_user_can_post_type( $post->post_type, 'edit_others_posts' ) ) {
					return false;
				}
				$status = (string) ( $input['status'] ?? '' );
				if ( in_array( $status, array( 'publish', 'future', 'private' ), true ) ) {
					return wbs_mcp_user_can_post_type( $post->post_type, 'publish_posts' );
				}
				return true;
			},
			'execute_callback'    => 'wbs_mcp_execute_update_content',
			'meta'                => $public_meta( array( 'readonly' => false, 'destructive' => false, 'idempotent' => false ) ),
		)
	);

	wp_register_ability(
		'wbs/trash-content',
		array(
			'label'               => 'Trash content',
			'description'         => '固定ページ・カスタム投稿タイプをゴミ箱へ移動する（完全削除はしない）。',
			'category'            => 'wbs-mcp',
			'input_schema'        => array(
				'type'       => 'object',
				'properties' => array(
					'id' => array(
						'type'    => 'integer',
						'minimum' => 1,
					),
				),
				'required'   => array( 'id' ),
			),
			'permission_callback' => static function ( $input ): bool {
				$post = wbs_mcp_guarded_post( $input['id'] ?? 0 );
				if ( is_wp_error( $post ) ) {
					return false;
				}
				return wbs_mcp_user_can_post_type( $post->post_type, 'delete_others_posts' );
			},
			'execute_callback'    => 'wbs_mcp_execute_trash_content',
			'meta'                => $public_meta( array( 'readonly' => false, 'destructive' => true, 'idempotent' => false ) ),
		)
	);

	// ---- タクソノミー（許可投稿タイプに紐づくもの）----

	wp_register_ability(
		'wbs/list-terms',
		array(
			'label'               => 'List terms',
			'description'         => '許可タクソノミーのターム一覧を取得する。',
			'category'            => 'wbs-mcp',
			'input_schema'        => array(
				'type'       => 'object',
				'properties' => array(
					'taxonomy'   => array(
						'type' => 'string',
						'enum' => $taxonomy_enum,
					),
					'search'     => array( 'type' => 'string' ),
					'hide_empty' => array(
						'type'    => 'boolean',
						'default' => false,
					),
				),
				'required'   => array( 'taxonomy' ),
			),
			'permission_callback' => static function ( $input ): bool {
				return wbs_mcp_user_can_taxonomy( (string) ( $input['taxonomy'] ?? '' ), 'manage_terms' );
			},
			'execute_callback'    => 'wbs_mcp_execute_list_terms',
			'meta'                => $public_meta( array( 'readonly' => true, 'destructive' => false, 'idempotent' => true ) ),
		)
	);

	wp_register_ability(
		'wbs/create-term',
		array(
			'label'               => 'Create term',
			'description'         => '許可タクソノミーにタームを作成する。',
			'category'            => 'wbs-mcp',
			'input_schema'        => array(
				'type'       => 'object',
				'properties' => array(
					'taxonomy'    => array(
						'type' => 'string',
						'enum' => $taxonomy_enum,
					),
					'name'        => array( 'type' => 'string' ),
					'slug'        => array( 'type' => 'string' ),
					'description' => array( 'type' => 'string' ),
					'parent'      => array(
						'type'    => 'integer',
						'minimum' => 0,
						'default' => 0,
					),
				),
				'required'   => array( 'taxonomy', 'name' ),
			),
			'permission_callback' => static function ( $input ): bool {
				return wbs_mcp_user_can_taxonomy( (string) ( $input['taxonomy'] ?? '' ), 'edit_terms' );
			},
			'execute_callback'    => 'wbs_mcp_execute_create_term',
			'meta'                => $public_meta( array( 'readonly' => false, 'destructive' => false, 'idempotent' => false ) ),
		)
	);

	wp_register_ability(
		'wbs/update-term',
		array(
			'label'               => 'Update term',
			'description'         => '許可タクソノミーのタームを更新する。',
			'category'            => 'wbs-mcp',
			'input_schema'        => array(
				'type'       => 'object',
				'properties' => array(
					'taxonomy'    => array(
						'type' => 'string',
						'enum' => $taxonomy_enum,
					),
					'id'          => array(
						'type'    => 'integer',
						'minimum' => 1,
					),
					'name'        => array( 'type' => 'string' ),
					'slug'        => array( 'type' => 'string' ),
					'description' => array( 'type' => 'string' ),
					'parent'      => array(
						'type'    => 'integer',
						'minimum' => 0,
					),
				),
				'required'   => array( 'taxonomy', 'id' ),
			),
			'permission_callback' => static function ( $input ): bool {
				return wbs_mcp_user_can_taxonomy( (string) ( $input['taxonomy'] ?? '' ), 'edit_terms' );
			},
			'execute_callback'    => 'wbs_mcp_execute_update_term',
			'meta'                => $public_meta( array( 'readonly' => false, 'destructive' => false, 'idempotent' => false ) ),
		)
	);
}
add_action( 'wp_abilities_api_init', 'wbs_mcp_register_abilities' );

/**
 * wbs/list-content 実行。
 *
 * @param array<string, mixed> $input 入力。
 * @return array<string, mixed>
 */
function wbs_mcp_execute_list_content( array $input = array() ): array {
	$query = new WP_Query(
		array(
			'post_type'      => (string) $input['post_type'],
			'post_status'    => (string) ( $input['status'] ?? 'any' ),
			's'              => isset( $input['search'] ) ? (string) $input['search'] : '',
			'posts_per_page' => isset( $input['per_page'] ) ? absint( $input['per_page'] ) : 20,
			'paged'          => isset( $input['page'] ) ? absint( $input['page'] ) : 1,
		)
	);

	$items = array();
	foreach ( $query->posts as $post ) {
		$items[] = wbs_mcp_post_summary( $post );
	}

	return array(
		'items' => $items,
		'total' => (int) $query->found_posts,
		'pages' => (int) $query->max_num_pages,
	);
}

/**
 * wbs/get-content 実行。
 *
 * @param array<string, mixed> $input 入力。
 * @return array<string, mixed>|WP_Error
 */
function wbs_mcp_execute_get_content( array $input = array() ) {
	$post = wbs_mcp_guarded_post( $input['id'] ?? 0 );
	if ( is_wp_error( $post ) ) {
		return $post;
	}

	$terms = array();
	foreach ( get_object_taxonomies( $post->post_type ) as $taxonomy ) {
		if ( ! in_array( $taxonomy, wbs_mcp_allowed_taxonomies(), true ) ) {
			continue;
		}
		$assigned = wp_get_object_terms( $post->ID, $taxonomy );
		if ( is_wp_error( $assigned ) ) {
			continue;
		}
		$terms[ $taxonomy ] = array_map(
			static function ( $term ): array {
				return array(
					'id'   => (int) $term->term_id,
					'name' => (string) $term->name,
					'slug' => (string) $term->slug,
				);
			},
			$assigned
		);
	}

	$data            = wbs_mcp_post_summary( $post );
	$data['content'] = (string) $post->post_content;
	$data['excerpt'] = (string) $post->post_excerpt;
	$data['author']  = (int) $post->post_author;
	$data['date']    = (string) $post->post_date_gmt;
	$data['terms']   = $terms;

	return $data;
}

/**
 * wbs/create-content 実行。
 *
 * @param array<string, mixed> $input 入力。
 * @return array<string, mixed>|WP_Error
 */
function wbs_mcp_execute_create_content( array $input = array() ) {
	$postarr = array(
		'post_type'    => (string) $input['post_type'],
		'post_title'   => (string) $input['title'],
		'post_content' => isset( $input['content'] ) ? (string) $input['content'] : '',
		'post_excerpt' => isset( $input['excerpt'] ) ? (string) $input['excerpt'] : '',
		'post_status'  => isset( $input['status'] ) ? (string) $input['status'] : 'draft',
	);
	if ( isset( $input['slug'] ) && '' !== $input['slug'] ) {
		$postarr['post_name'] = sanitize_title( (string) $input['slug'] );
	}

	$post_id = wp_insert_post( $postarr, true );
	if ( is_wp_error( $post_id ) ) {
		return $post_id;
	}

	$post = get_post( $post_id );
	if ( $post instanceof WP_Post && isset( $input['terms'] ) && is_array( $input['terms'] ) ) {
		wbs_mcp_assign_terms( $post, $input['terms'] );
	}

	return wbs_mcp_post_summary( get_post( $post_id ) );
}

/**
 * wbs/update-content 実行。
 *
 * @param array<string, mixed> $input 入力。
 * @return array<string, mixed>|WP_Error
 */
function wbs_mcp_execute_update_content( array $input = array() ) {
	$post = wbs_mcp_guarded_post( $input['id'] ?? 0 );
	if ( is_wp_error( $post ) ) {
		return $post;
	}

	$postarr = array( 'ID' => $post->ID );
	if ( isset( $input['title'] ) ) {
		$postarr['post_title'] = (string) $input['title'];
	}
	if ( isset( $input['content'] ) ) {
		$postarr['post_content'] = (string) $input['content'];
	}
	if ( isset( $input['excerpt'] ) ) {
		$postarr['post_excerpt'] = (string) $input['excerpt'];
	}
	if ( isset( $input['status'] ) ) {
		$postarr['post_status'] = (string) $input['status'];
	}
	if ( isset( $input['slug'] ) && '' !== $input['slug'] ) {
		$postarr['post_name'] = sanitize_title( (string) $input['slug'] );
	}

	$result = wp_update_post( $postarr, true );
	if ( is_wp_error( $result ) ) {
		return $result;
	}

	if ( isset( $input['terms'] ) && is_array( $input['terms'] ) ) {
		wbs_mcp_assign_terms( $post, $input['terms'] );
	}

	return wbs_mcp_post_summary( get_post( $post->ID ) );
}

/**
 * wbs/trash-content 実行。
 *
 * @param array<string, mixed> $input 入力。
 * @return array<string, mixed>|WP_Error
 */
function wbs_mcp_execute_trash_content( array $input = array() ) {
	$post = wbs_mcp_guarded_post( $input['id'] ?? 0 );
	if ( is_wp_error( $post ) ) {
		return $post;
	}

	$trashed = wp_trash_post( $post->ID );
	if ( ! $trashed instanceof WP_Post ) {
		return new WP_Error( 'wbs_mcp_trash_failed', __( 'ゴミ箱への移動に失敗しました。', 'wbs' ) );
	}

	// wp_trash_post() はトラッシュ前の post を返すため、最新状態を取り直す。
	$current = get_post( $post->ID );

	return array(
		'id'     => (int) $post->ID,
		'status' => $current instanceof WP_Post ? (string) $current->post_status : 'trash',
	);
}

/**
 * wbs/list-terms 実行。
 *
 * @param array<string, mixed> $input 入力。
 * @return array<string, mixed>|WP_Error
 */
function wbs_mcp_execute_list_terms( array $input = array() ) {
	$terms = get_terms(
		array(
			'taxonomy'   => (string) $input['taxonomy'],
			'hide_empty' => ! empty( $input['hide_empty'] ),
			'search'     => isset( $input['search'] ) ? (string) $input['search'] : '',
		)
	);
	if ( is_wp_error( $terms ) ) {
		return $terms;
	}

	$items = array();
	foreach ( $terms as $term ) {
		$items[] = array(
			'id'       => (int) $term->term_id,
			'name'     => (string) $term->name,
			'slug'     => (string) $term->slug,
			'parent'   => (int) $term->parent,
			'count'    => (int) $term->count,
			'taxonomy' => (string) $term->taxonomy,
		);
	}

	return array( 'items' => $items );
}

/**
 * wbs/create-term 実行。
 *
 * @param array<string, mixed> $input 入力。
 * @return array<string, mixed>|WP_Error
 */
function wbs_mcp_execute_create_term( array $input = array() ) {
	$result = wp_insert_term(
		(string) $input['name'],
		(string) $input['taxonomy'],
		array(
			'slug'        => isset( $input['slug'] ) ? sanitize_title( (string) $input['slug'] ) : '',
			'description' => isset( $input['description'] ) ? (string) $input['description'] : '',
			'parent'      => isset( $input['parent'] ) ? absint( $input['parent'] ) : 0,
		)
	);
	if ( is_wp_error( $result ) ) {
		return $result;
	}

	return array(
		'id'       => (int) $result['term_id'],
		'taxonomy' => (string) $input['taxonomy'],
	);
}

/**
 * wbs/update-term 実行。
 *
 * @param array<string, mixed> $input 入力。
 * @return array<string, mixed>|WP_Error
 */
function wbs_mcp_execute_update_term( array $input = array() ) {
	$taxonomy = (string) $input['taxonomy'];
	$term_id  = absint( $input['id'] );

	$term = get_term( $term_id, $taxonomy );
	if ( ! $term instanceof WP_Term ) {
		return new WP_Error( 'wbs_mcp_term_not_found', __( '指定されたタームが見つかりません。', 'wbs' ) );
	}

	$args = array();
	if ( isset( $input['name'] ) ) {
		$args['name'] = (string) $input['name'];
	}
	if ( isset( $input['slug'] ) && '' !== $input['slug'] ) {
		$args['slug'] = sanitize_title( (string) $input['slug'] );
	}
	if ( isset( $input['description'] ) ) {
		$args['description'] = (string) $input['description'];
	}
	if ( isset( $input['parent'] ) ) {
		$args['parent'] = absint( $input['parent'] );
	}

	$result = wp_update_term( $term_id, $taxonomy, $args );
	if ( is_wp_error( $result ) ) {
		return $result;
	}

	return array(
		'id'       => (int) $result['term_id'],
		'taxonomy' => $taxonomy,
	);
}
