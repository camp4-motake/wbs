<?php

/**
 * Shared Script Modules
 *
 * Registers theme-level WordPress Script Modules that blocks reference as
 * shared dependencies. Currently the `@wbs/slider` module (embla-carousel +
 * helpers) built from `src/slider`.
 *
 * Consuming blocks load it lazily from their view module:
 *   const { createSlider } = await import( '@wbs/slider' );
 *
 * For the bare specifier to resolve at runtime it must appear in WordPress'
 * import map. WordPress only adds the *dependencies* of enqueued script modules
 * to the import map — enqueued modules themselves are printed as `<script>`
 * tags, never listed in the map (see WP_Script_Modules::get_import_map()). So
 * `@wbs/slider` must be declared as a dependency of each consuming block's view
 * module (see register_shared_module_consumers()); it is NOT enqueued directly.
 *
 * @package wbs
 */

namespace Site\Theme\ScriptModules;

/**
 * Register shared script modules (registration only; consumers declare them as
 * dependencies, see register_shared_module_consumers()).
 *
 * @return void
 */
function register_script_modules() {

	// Registry of shared modules: import-map specifier => build directory.
	// The key is the specifier consuming blocks import() and declare as a
	// dependency of their view module, so it must match their usage; add one
	// entry per module (mirrors the map-and-loop pattern in assets.php).
	$modules = array(
		'@wbs/slider' => 'build/slider',
	);

	foreach ( $modules as $id => $build_dir ) {
		$asset_path = get_theme_file_path( $build_dir . '/index.asset.php' );
		$script     = $build_dir . '/index.js';

		if ( ! file_exists( $asset_path ) || ! file_exists( get_theme_file_path( $script ) ) ) {
			continue;
		}

		$manifest = \Site\Theme\Assets\read_asset_manifest( $asset_path );

		wp_register_script_module(
			$id,
			get_theme_file_uri( $script ),
			$manifest['dependencies'],
			$manifest['version']
		);
	}
}
add_action( 'init', __NAMESPACE__ . '\\register_script_modules' );

/**
 * Declare shared script modules as dependencies of the blocks that lazy-import
 * them.
 *
 * WordPress prints enqueued script modules as `<script type="module">` tags and
 * adds only their *dependencies* to the import map — never the enqueued modules
 * themselves (see WP_Script_Modules::get_import_map()). A module reached purely
 * through a bare-specifier dynamic import (`import( '@wbs/slider' )`) therefore
 * has to be a dependency of the consuming block's view module, or its specifier
 * never makes it into the import map and the browser throws
 * "Failed to resolve module specifier '@wbs/slider'".
 *
 * Block view modules are auto-registered from block.json with the dependencies
 * recorded in their build asset file. Because consumers import `@wbs/slider`
 * with `webpackIgnore` (to keep webpack zero-config and avoid bundling embla
 * into every block), the dependency is invisible to the build, so we add it
 * here: re-register each consumer's view module with `@wbs/slider` appended as
 * a *dynamic* dependency. A dynamic dependency lands in the import map without
 * being eagerly printed as a script, so embla still loads on demand.
 *
 * Runs at priority 20, after blocks register at the default priority 10 in
 * register-blocks.php.
 *
 * @return void
 */
function register_shared_module_consumers() {

	// Consumer block name => its built view module, relative to the theme root
	// (without extension). Each block's view module lazy-imports '@wbs/slider'.
	// Add one entry per block that consumes a shared module this way, e.g.
	//   'wbs/article-slider' => 'build/blocks/article-slider/view',
	$consumers = array();

	foreach ( $consumers as $block_name => $view_base ) {
		$asset_path = get_theme_file_path( $view_base . '.asset.php' );
		$script     = $view_base . '.js';

		if ( ! file_exists( $asset_path ) || ! file_exists( get_theme_file_path( $script ) ) ) {
			continue;
		}

		$manifest       = \Site\Theme\Assets\read_asset_manifest( $asset_path );
		$dependencies   = $manifest['dependencies'];
		$dependencies[] = array(
			'id'     => '@wbs/slider',
			'import' => 'dynamic',
		);

		$module_id = generate_block_asset_handle( $block_name, 'viewScriptModule' );

		// register() ignores already-registered ids, so deregister the
		// core-registered view module before re-registering it with the
		// shared module appended as a dependency.
		wp_deregister_script_module( $module_id );
		wp_register_script_module(
			$module_id,
			get_theme_file_uri( $script ),
			$dependencies,
			$manifest['version']
		);
	}
}
add_action( 'init', __NAMESPACE__ . '\\register_shared_module_consumers', 20 );
