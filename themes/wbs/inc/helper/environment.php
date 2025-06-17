<?php

/**
 * @package wbs
 */

namespace Site\Theme\Helper\Env;

/**
 * Production 判定
 *
 * @return boolean
 */
function in_production() {
	return wp_get_environment_type() === 'production';
}

/**
 * Staging 判定
 *
 * @return boolean
 */
function in_staging() {
	return wp_get_environment_type() === 'staging';
}

/**
 * ローカル環境判定
 *
 * @return boolean
 */
function in_local() {
	return wp_get_environment_type() === 'local';
}
