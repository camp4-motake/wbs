<?php

/**
 * User Hooks
 *
 * @package wbs
 */

namespace Site\Theme\Admin\User;

/**
 * 上位ユーザー以外はメディア表示を自分のアイテムのみに制限
 *
 * @param mixture $where .
 * @return mixture
 */
function media_display_limit_current_user( $where ) {

	if ( ! is_admin() || is_higher_role_user() ) {
		return $where;
	}

	$is_action = ! empty( $_POST['action'] ) &&
		'query-attachments' === wp_unslash( $_POST['action'] ) &&
		wp_verify_nonce( $_POST['_wpnonce'], 'query-attachments' );

	if ( $is_action ) {
		global $current_user;
		$where .= ' AND post_author=' . $current_user->data->ID;
	}
	return $where;
}
add_filter( 'posts_where', __NAMESPACE__ . '\\media_display_limit_current_user' );

/**
 * ヘルパー: 上位編集ユーザー（管理者or編集者）判定
 *
 * @return boolean
 */
function is_higher_role_user() {
	return current_user_can( 'edit_pages' );
}

/**
 * 指定ロール所属ユーザーID配列を取得
 *
 * @param string $cap .
 * @return array
 */
function get_user_cap_ids( $cap = '' ) {

	$store_users = get_users( array( 'role' => $cap ) );

	if ( ! $store_users ) {
		return array();
	}

	return array_map(
		function ( $user ) {
			return $user->ID;
		},
		$store_users
	);
}
