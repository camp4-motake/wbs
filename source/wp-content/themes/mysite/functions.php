<?php

/**
 * Theme functions
 *
 * @package wbs
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once __DIR__ . '/inc/constants.php';

// admin hooks
require_once __DIR__ . '/inc/admin/cleanup.php';
require_once __DIR__ . '/inc/admin/hooks.php';
require_once __DIR__ . '/inc/admin/login-logo.php';
require_once __DIR__ . '/inc/admin/menu-order.php';
require_once __DIR__ . '/inc/admin/user.php';

// plugin hooks
require_once __DIR__ . '/inc/plugins/acf.php';
require_once __DIR__ . '/inc/plugins/post-type-order.php';
require_once __DIR__ . '/inc/plugins/seo-framework.php';

// register post types
require_once __DIR__ . '/inc/post-type/news.php';

// register custom fields
foreach ( glob( __DIR__ . '/inc/custom-fields/*' ) as $_path ) {
	require_once $_path;
}

// register blocks
require_once __DIR__ . '/inc/blocks/register-blocks.php';

// helper
require_once __DIR__ . '/inc/helper/content.php';
require_once __DIR__ . '/inc/helper/environment.php';
require_once __DIR__ . '/inc/helper/image.php';
require_once __DIR__ . '/inc/helper/nav.php';
require_once __DIR__ . '/inc/helper/pagination.php';
require_once __DIR__ . '/inc/helper/path.php';
require_once __DIR__ . '/inc/helper/select.php';
require_once __DIR__ . '/inc/helper/util.php';

// theme setup
require_once __DIR__ . '/inc/assets.php';
require_once __DIR__ . '/inc/cleanup.php';
require_once __DIR__ . '/inc/editor.php';
require_once __DIR__ . '/inc/googlefonts.php';
require_once __DIR__ . '/inc/images.php';
require_once __DIR__ . '/inc/kses.php';
require_once __DIR__ . '/inc/meta.php';
require_once __DIR__ . '/inc/setup.php';
require_once __DIR__ . '/inc/siteicon.php';
require_once __DIR__ . '/inc/tracking.php';
