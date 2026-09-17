# MCP 経由で WordPress のデータを取得する手順

ローカルの `wordpress-http-default` MCP サーバー（`.mcp.json` で定義）から WP のデータを
取得できるようにするための手順です。

> **⚠️ 試験的な仕組みです。** このドキュメントと関連する mu-plugin
> （`mu-plugins/_wp-adapter.php`）は動作確認・検証目的で暫定的に設置しているものです。
> 恒久的な機能として保証されたものではなく、**今後予告なく削除・変更される可能性があります。**
> 本番環境には持ち込まず、ローカル(wp-env)での検証用途に限定して利用してください。

## 仕組み（前提）

- `.mcp.json` の MCP サーバーは `@automattic/mcp-wordpress-remote` を介して
  WP の REST エンドポイント `…/wp-json/mcp/mcp-adapter-default-server` に接続する。
- サーバー側は **`mcp-adapter` プラグイン**（動作確認時 v0.5.0）が処理する。
- MCP クライアントに公開されるツールは実質 3 つのプロキシだけ:
  - `mcp-adapter-discover-abilities` … 公開中の ability 一覧を返す
  - `mcp-adapter-get-ability-info` … ability の入出力スキーマを返す
  - `mcp-adapter-execute-ability` … ability を実行する
- これらのプロキシは **`meta.mcp.public === true` を持つ ability だけ** を対象にする
  （`plugins/mcp-adapter/includes/Abilities/McpAbilityHelperTrait.php:39`）。
  フラグが無い ability は discover に出ず、execute すると
  `Ability "…" is not exposed via MCP (mcp.public!=true)` で拒否される。

> つまり「MCP からデータを取る」＝「`meta.mcp.public => true` を付けた ability を登録する」。
> WP コア同梱の `core/get-site-info` 等はこのフラグを持たないため、そのままでは取得できない。

## mcp-adapter プラグインと接続のセットアップ

ability を登録する前に、サーバー側（mcp-adapter プラグイン）とクライアント側
（`.mcp.json`）の接続を用意する。ローカル(wp-env)前提の手順。

### 前提

- **Abilities API** … WP コア標準（本リポは WordPress 7.0+ 想定なので追加不要）。
- **mcp-adapter** … GitHub 配布のプラグイン（<https://github.com/WordPress/mcp-adapter>、
  動作確認時 v0.5.0 / Requires WP 6.8+）。wp.org 未配布のためローカル導入する。
- `.wp-env.json` が `./plugins` を `wp-content/plugins` にマウントするため、
  `plugins/` 配下に置いたプラグインはそのまま wp-env で読み込まれる。
  なお `plugins/*` は `.gitignore` 済み（`plugins/wbs-*` のみ追跡）なので、
  mcp-adapter はコミットされない = 各自の環境でローカル導入する。

### 1. プラグインを導入して有効化

`plugins/mcp-adapter/` に配置する。リリースの zip を展開するか、タグ指定で clone:

```sh
git clone --depth 1 --branch 0.5.0 \
  https://github.com/WordPress/mcp-adapter.git plugins/mcp-adapter

npm run cli -- wp plugin activate mcp-adapter
```

有効化を確認:

```sh
npm run cli -- wp plugin list --status=active | grep mcp-adapter
```

有効化すると REST 側にデフォルト MCP サーバーのルートが生える:

```
/wp-json/mcp/mcp-adapter-default-server
```

### 2. 認証用のアプリケーションパスワードを発行

MCP クライアントは Basic 認証（ユーザー名 + アプリケーションパスワード）で接続する。
admin ユーザー向けに発行する:

```sh
npm run cli -- wp user application-password create admin "MCP Local" --porcelain
```

出力される `xxxx xxxx xxxx xxxx xxxx xxxx`（スペース区切り）を控える。
※ 通常ログインのパスワードではなくアプリケーションパスワードを使う。

### 3. `.mcp.json` を作成

リポジトリルートに `.mcp.json` を置く（`.gitignore` 済み・git 追跡外）。
`@automattic/mcp-wordpress-remote` が WP の REST エンドポイントへプロキシする:

```json
{
	"mcpServers": {
		"wordpress-http-default": {
			"command": "npx",
			"args": ["-y", "@automattic/mcp-wordpress-remote@latest"],
			"env": {
				"WP_API_URL": "http://localhost:4092/wp-json/mcp/mcp-adapter-default-server",
				"LOG_FILE": "/absolute/path/to/repo/logs/mcp-adapter.log",
				"WP_API_USERNAME": "admin",
				"WP_API_PASSWORD": "xxxx xxxx xxxx xxxx xxxx xxxx"
			}
		}
	}
}
```

- `WP_API_URL` … wp-env の dev ポート（本リポは `4092`）+ デフォルトサーバールート。
- `WP_API_PASSWORD` … 手順 2 のアプリケーションパスワード（スペース込みでそのまま）。
- `LOG_FILE` … 任意。プロキシのデバッグログ出力先（`logs/` も `.gitignore` 済み）。

MCP クライアント（Claude Code 等）を再起動して設定を読み込ませる。

### 4. 接続確認

`mcp-adapter-discover-abilities` を実行し、`{"abilities":[]}` 以外
（＝公開中の ability が並ぶ）が返れば疎通・認証ともに OK。

> この時点で公開 ability が 0 件でも接続自体は成功している。
> 次章の手順で `meta.mcp.public => true` の ability を登録すると一覧に出てくる。

## 手順

### 1. ability を登録するファイルを追加

テーマ規約（`inc/` 配下・`Site\Theme\*` 名前空間・`add_action` パターン）に沿って
新規ファイルを作成する。ability の登録フックは **`wp_abilities_api_init`**。

`themes/wbs/inc/abilities.php`:

```php
<?php
/**
 * MCP 経由で公開する WordPress Abilities。
 *
 * @package Site\Theme\Abilities
 */

declare( strict_types=1 );

namespace Site\Theme\Abilities;

/**
 * カスタム ability を登録する。
 */
function register_abilities(): void {
	if ( ! function_exists( 'wp_register_ability' ) ) {
		return; // Abilities API 未対応環境では何もしない。
	}

	wp_register_ability(
		'wbs/get-recent-posts',
		array(
			'label'               => 'Get Recent Posts',
			'description'         => 'Return the most recent published posts (id, title, link).',
			'category'            => 'mcp-adapter',
			'input_schema'        => array(
				'type'       => 'object',
				'properties' => array(
					'per_page' => array(
						'type'    => 'integer',
						'minimum' => 1,
						'maximum' => 50,
						'default' => 5,
					),
				),
			),
			'output_schema'       => array(
				'type'       => 'object',
				'properties' => array(
					'posts' => array(
						'type'  => 'array',
						'items' => array(
							'type'       => 'object',
							'properties' => array(
								'id'    => array( 'type' => 'integer' ),
								'title' => array( 'type' => 'string' ),
								'link'  => array( 'type' => 'string' ),
							),
						),
					),
				),
			),
			// 誰が実行できるか。読み取り専用でも権限は必ず絞る。
			'permission_callback' => static function (): bool {
				return current_user_can( 'read' );
			},
			'execute_callback'    => __NAMESPACE__ . '\\get_recent_posts',
			// ★ これが無いと MCP から見えない・実行できない。
			'meta'                => array(
				'mcp'         => array(
					'public' => true,
					'type'   => 'tool', // tool | resource | prompt。省略時は tool。
				),
				'annotations' => array(
					'readonly'    => true,
					'destructive' => false,
					'idempotent'  => true,
				),
			),
		)
	);
}
add_action( 'wp_abilities_api_init', __NAMESPACE__ . '\\register_abilities' );

/**
 * 直近の公開投稿を返す。
 *
 * @param array $input 入力パラメータ。
 * @return array
 */
function get_recent_posts( array $input = array() ): array {
	$per_page = isset( $input['per_page'] ) ? absint( $input['per_page'] ) : 5;

	$query = new \WP_Query(
		array(
			'post_type'      => 'post',
			'post_status'    => 'publish',
			'posts_per_page' => min( 50, max( 1, $per_page ) ),
			'no_found_rows'  => true,
		)
	);

	$posts = array();
	foreach ( $query->posts as $post ) {
		$posts[] = array(
			'id'    => (int) $post->ID,
			'title' => get_the_title( $post ),
			'link'  => (string) get_permalink( $post ),
		);
	}

	return array( 'posts' => $posts );
}
```

### 2. functions.php の include マニフェストに追記

`themes/wbs/functions.php` の「Setup」ブロック（`inc/` の require 群）に 1 行足す:

```php
require_once __DIR__ . '/inc/abilities.php';
```

### 3. 反映（キャッシュがある場合のみ）

コード追加だけで有効になる。REST ルートや ability が反映されないときは:

```sh
npm run wp-flush   # rewrite ルールのハード flush
```

### 4. 動作確認

MCP クライアント（このセッション）から:

1. `mcp-adapter-discover-abilities` → 一覧に `wbs/get-recent-posts` が出る。
2. `mcp-adapter-get-ability-info`（`ability_name: "wbs/get-recent-posts"`）→ スキーマが返る。
3. `mcp-adapter-execute-ability`
   （`ability_name: "wbs/get-recent-posts"`, `parameters: { "per_page": 3 }`）→ 投稿データが返る。

WP-CLI 側で登録状況を直接確認する場合:

```sh
npm run cli -- wp eval 'foreach ( wp_get_abilities() as $a ) { echo $a->get_name(), "\n"; }'
```

## 補足

- **既存のコア ability を公開したいだけの場合**は、独自 ability を作らずに
  対象 ability の `meta.mcp.public` を立てる方法もある（対応するメタフィルタが
  提供されているか要確認）。ただし読み取り範囲・権限を明示できる点で、
  用途に合わせた薄い ability を自前登録するほうが安全で推奨。
- **tool 以外（resource / prompt）** は `meta.mcp.type` を指定すれば
  デフォルトサーバーが自動的に拾う（`DefaultServerFactory::discover_abilities_by_type()`）。
  tool は上記プロキシ経由で実行される。
- 認証情報（`.mcp.json` の `WP_API_PASSWORD` = アプリケーションパスワード）は
  `.gitignore` 済みで git 追跡対象外。共有時は伏せること。
- `permission_callback` は必ず適切な権限で絞る。MCP は admin のアプリケーション
  パスワードで認証しているため、緩いコールバックだと不要に広い操作を許してしまう。
