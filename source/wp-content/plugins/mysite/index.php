<?php

/**
 * Plugin Name:       mysite plugin
 * Description:       Custom Site Plugin
 * Requires at least: 6.4
 * Requires PHP:      8.1
 * Version:           0.1.0
 * Author:            CAMP4 inc.
 * License:           GPL-2.0-or-later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       mysite
 *
 * @package           mysite
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

require_once 'inc/acf/acf-json.php';
require_once 'inc/blocks/register-blocks.php';
