<?php

namespace Site\Theme\ImageHooks;

/* // phpcs:ignore Squiz.PHP.CommentedOutCode.Found

usage

$image_attributes = array(
	'sizes' => '(max-width: 860px) 100vw, 860px',
	'class' => 'custom-image',
	'loading' => 'lazy'
);

wp_get_attachment_image(
	$attachment_id,
	'full', // または適切なサイズ
	false,
	$image_attributes
);

*/

/**
 * SP用サイズを追加
 */
function custom_image_size( /* $max_width */ ) {
	add_image_size( 'sm-size', 860, 860, false );
	// add_image_size( 'md-size', 1024, 1024, false );  phpcs:ignore Squiz.PHP.CommentedOutCode.Found

	/* // phpcs:ignore Squiz.PHP.CommentedOutCode.Found
	// WORKAROUND: srcset 削除
	add_filter( 'wp_calculate_image_srcset', '__return_false' );
	add_filter( 'wp_calculate_image_sizes', '__return_false' );
	*/
}
add_filter( 'init', __NAMESPACE__ . '\\custom_image_size' );

/**
 * image_attributeのデフォルト値変更
 */
function custom_image_attributes( $attr ) {
	if ( ! isset( $attr['sizes'] ) ) {
		$attr['sizes'] = '(max-width: 860px) 100vw, 860px';
	}

	return $attr;
}
add_filter( 'wp_get_attachment_image_attributes', __NAMESPACE__ . '\\custom_image_attributes', 10, 3 );

/**
 * 最大画像サイズ設定
 */
function custom_max_srcset_image_width( /* $max_width */ ) {
	return 2560;
}
add_filter( 'max_srcset_image_width', __NAMESPACE__ . '\\custom_max_srcset_image_width' );

/**
 * JPEG画質の調整
 */
function custom_jpeg_quality() {
	return 90; // 画質設定（デフォルトは82）
}
add_filter( 'jpeg_quality', __NAMESPACE__ . '\\custom_jpeg_quality' );

/**
 * リサイズ時の画質設定
 */
function custom_image_editor_quality( /*  $quality  */ ) {
	return 90;
}
add_filter( 'wp_editor_set_quality', __NAMESPACE__ . '\\custom_image_editor_quality' );


/**
 * wp_get_attachment_image の srcset 出力サイズ制限
 *
 * ex)
 * $medium_width = get_option( 'medium_size_w' )
 * $large_width  = get_option( 'large_size_w' );
 *
 * args: $sources, $size_array, $image_src, $image_meta, $attachment_id
 */
function calculate_image_srcset_size_limitation( $sources ) {
	if ( is_admin() ) {
		return $sources;
	}

	if ( ! $sources ) {
		return $sources;
	}

	// 最小画像サイズ
	$min_size = 960;

	// sourcesから $min_size 未満を削除
	$filtered_sources = array_filter(
		$sources,
		function ( $source ) use ( $min_size ) {
			return isset( $source['value'] ) &&
				is_numeric( $source['value'] ) &&
				$source['value'] >= $min_size;
		}
	);

	return $filtered_sources;
}
add_filter( 'wp_calculate_image_srcset', __NAMESPACE__ . '\\calculate_image_srcset_size_limitation', 10, 5 );
