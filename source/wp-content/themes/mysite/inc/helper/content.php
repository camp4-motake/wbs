<?php

/**
 * Helper Content
 *
 * @package wbs
 */

namespace Site\Theme\Helper\Content;

/**
 * newバッジの表示判定 (デフォルトは15日間)
 */
function new_badge_display( $limit_day = 15, $badge_html = '<span class="badge-new">New</span>' ) {
	$days     = $limit_day;
	$days_int = ( $days - 1 ) * 86400;
	$dayago   = time() - get_the_time( 'U' );
	$badge    = $dayago < $days_int ? $badge_html : '';

	return $badge;
}
