<?php

/**
 * Image Helper
 *
 * @package wbs
 */

namespace Site\Theme\Helper\Image;

use Site\Theme\Helper\Path;

/**
 * wp_get_attachment_image without srcset
 *
 * @param number $attachment_id
 * @param string $size
 * @param boolean $icon
 * @param string $attr
 * @return string
 */
function wp_get_attachment_image_without_srcset( $attachment_id, $size = 'full', $icon = false, $attr = '' ) {
	add_filter( 'wp_calculate_image_srcset_meta', '__return_null' );
	$result = wp_get_attachment_image( $attachment_id, $size, $icon, $attr );
	remove_filter( 'wp_calculate_image_srcset_meta', '__return_null' );
	return $result;
}


/**
 * Inline SVG
 *
 * @param String $filepath svg path.
 * @param String $title title text.
 * @return String
 */
function inline_svg( string $filepath = '', string $title = '' ) {
	$svg_asset_path = get_theme_file_path( $filepath );

	if ( ! file_exists( $svg_asset_path ) ) {
		return '';
	}

	// WORKAROUND
	// phpcs:ignore WordPress.WP.AlternativeFunctions.file_get_contents_file_get_contents
	$svg_source = file_get_contents( esc_url_raw( $svg_asset_path ) );

	if ( $title && '' !== $title ) {
		$pattern    = '/<title>.*?<\/title>/u';
		$replace    = "<title>{$title}</title>";
		$svg_source = preg_replace( $pattern, $replace, $svg_source );
	}
	return $svg_source;
}

/**
 * svg kses rule set
 *
 * https://gist.github.com/TremiDkhar/a5f0a761c42bf4135e05b05ce829d78f
 * @return array
 */
function kses_svg() {
	$kses_defaults = wp_kses_allowed_html( 'post' );
	$svg_args      = array(
		'svg'    => array(
			'id'              => true,
			'class'           => true,
			'aria-hidden'     => true,
			'aria-labelledby' => true,
			'role'            => true,
			'xmlns'           => true,
			'width'           => true,
			'height'          => true,
			'viewbox'         => true, // <= Must be lower case!
		),
		'g'      => array( 'fill' => true ),
		'title'  => array( 'title' => true ),
		'path'   => array(
			'd'    => true,
			'fill' => true,
		),
		'circle' => array(
			'cx'   => true,
			'cy'   => true,
			'r'    => true,
			'fill' => true,
		),
		'rect'   => array(
			'fill'      => true,
			'fill-rule' => true,
			'height'    => true,
			'width'     => true,
			'x'         => true,
			'y'         => true,
		),
	);
	return array_merge( $kses_defaults, $svg_args );
}

/**
 * SVG Sprite tag
 *
 * @param string $xlink .
 * @param string $title .
 * @param string $class_name_string .
 * @param array  $attr .
 * @param string $role .
 * @return string
 */
function svg_sprite( $xlink = '', $title = '', $class_name_string = '', $attr = array(), $role = 'img' ): string {
	$attr = array_merge(
		array(
			'xmlns:xlink' => 'http://www.w3.org/1999/xlink',
			'xlink:href'  => '#svg--' . esc_attr( $xlink ),
		),
		$attr
	);

	$use_attr   = array_to_attr_string( $attr );
	$class_name = $class_name_string ? 'class="' . esc_attr( $class_name_string ) . '" ' : '';

	$xml = '<svg ' . $class_name . 'role="' . esc_attr( $role ) . '">';
	if ( ! empty( $title ) ) {
		$xml .= '<title>' . esc_attr( $title ) . '</title>';
	}
	$xml .= '<use ' . $use_attr . '></use></svg>';

	return $xml;
}

/**
 * Auto Image tag
 *
 * @param string|array $path path or array with ['img' => '...', 'source' => [...]].
 * @param array        $attrs attr.
 * @param boolean|array $is_origin_or_picture_attrs use old image format or picture attrs.
 * @param array        $picture_attrs picture attr (if 3rd arg is boolean).
 * @return string
 */
function auto_img( $path = '', $attrs = array(), $is_origin_or_picture_attrs = true, $picture_attrs = array() ) {
	$img_src = '';
	$sources = array();

	if ( is_array( $path ) && isset( $path['img'] ) ) {
		$img_src = $path['img'];
		if ( isset( $path['source'] ) ) {
			$sources = $path['source'];
		}
	} elseif ( is_string( $path ) ) {
		$img_src = $path;
	} else {
		return '';
	}

	if ( ! $img_src ) {
		return '';
	}

	$is_origin            = true;
	$actual_picture_attrs = array();

	if ( is_array( $is_origin_or_picture_attrs ) ) {
		$actual_picture_attrs = $is_origin_or_picture_attrs;
	} else {
		$is_origin            = $is_origin_or_picture_attrs;
		$actual_picture_attrs = $picture_attrs;
	}

	$find_variants = function ( $file_path ) use ( $is_origin ) {
		$variants       = array();
		$order_patterns = array(
			'png'  => array( 'avif', 'webp', 'png' ),
			'jpg'  => array( 'avif', 'webp', 'jpg' ),
			'webp' => array( 'avif', 'webp' ),
		);

		$info = pathinfo( $file_path );
		if ( ! isset( $info['extension'] ) ) {
			return file_exists( get_theme_file_path( $file_path ) ) ? array( $file_path ) : array();
		}
		$ext = $info['extension'];

		if ( ! isset( $order_patterns[ $ext ] ) ) {
			return file_exists( get_theme_file_path( $file_path ) ) ? array( $file_path ) : array();
		}

		foreach ( $order_patterns[ $ext ] as $format_ext ) {
			if ( $ext === $format_ext && ! $is_origin ) {
				continue;
			}

			$candidate = str_replace( ".{$ext}", ".{$format_ext}", $file_path );
			// Check distinct file path to avoid redundant checks if extension matches
			if ( file_exists( get_theme_file_path( $candidate ) ) ) {
				$variants[] = $candidate;
			}
		}
		return $variants;
	};

	$source_html = '';

	if ( ! empty( $sources ) ) {
		foreach ( $sources as $param ) {
			if ( empty( $param['src'] ) || empty( $param['media'] ) ) {
				continue;
			}
			$source_variants = $find_variants( $param['src'] );
			foreach ( $source_variants as $v ) {
				$source_html .= make_source_tag( $v, $param['media'] );
			}
		}
	}

	$main_variants = $find_variants( $img_src );

	if ( empty( $main_variants ) ) {
		return '';
	}

	$img_tag      = '';
	$main_sources = '';

	$last_variant = array_pop( $main_variants );
	$img_tag      = make_img_tag( $last_variant, $attrs );

	foreach ( $main_variants as $v ) {
		$main_sources .= make_source_tag( $v );
	}

	$has_sources   = ! empty( $source_html ) || ! empty( $main_sources );
	$has_pic_attrs = ! empty( $actual_picture_attrs );

	if ( $has_sources || $has_pic_attrs ) {
		$pic_attr_str = array_to_attr_string( $actual_picture_attrs );
		return "<picture{$pic_attr_str}>\n" . $source_html . $main_sources . $img_tag . "</picture>\n";
	}

	return $img_tag;
}

/**
 * Make image tag
 *
 * @param string $path .
 * @param array  $attrs .
 * @return string
 */
function make_img_tag( $path = '', $attrs = array() ) {
	$img_path = get_theme_file_path( $path );
	if ( ! file_exists( $img_path ) ) {
		return '';
	}
	$img_size = getimagesize( $img_path );
	$attrs    = wp_parse_args(
		$attrs,
		array(
			'width'    => ! empty( $img_size[0] ) ? $img_size[0] : '',
			'height'   => ! empty( $img_size[1] ) ? $img_size[1] : '',
			'alt'      => '',
			'decoding' => 'async',
		)
	);
	return '<img src="' . esc_url( Path\cache_buster( $path ) ) . '"' . array_to_attr_string( $attrs ) . '/>' . "\n";
}

/**
 * Make Source tag
 *
 * @param string $path .
 * @param string $media .
 * @return string
 */
function make_source_tag( $path = '', $media = null ) {
	$source_path = get_theme_file_path( $path );
	if ( ! file_exists( $source_path ) ) {
		return '';
	}
	$source_size = getimagesize( $source_path );
	$attrs       = array(
		'width'  => ! empty( $source_size[0] ) ? $source_size[0] : '',
		'height' => ! empty( $source_size[1] ) ? $source_size[1] : '',
		'type'   => ! empty( $source_size['mime'] ) ? $source_size['mime'] : '',
	);
	if ( ! empty( $media ) ) {
		$attrs = wp_parse_args( $attrs, array( 'media' => $media ) );
	}
	return '<source srcset="' . esc_url( Path\cache_buster( $path ) ) . '"' . array_to_attr_string( $attrs ) . ' >' . "\n";
}

/**
 * Make srcset strings
 *
 * @param string  $filename .
 * @param array   $resolution .
 * @param boolean $non_1x .
 * @return string
 */
function get_srcset_attr( $filename = '', $resolution = array(), $non_1x = false ): string {
	$resolution    = array_merge(
		array(
			'1x' => '',
			'2x' => '@2x',
		),
		$resolution
	);
	$srcset        = array();
	$srcset_string = '';
	foreach ( $resolution as $key => $res ) {
		if ( '1x' === $key && true === $non_1x ) {
			continue;
		}
		$high_res_name = preg_replace( '/\.[^.]s+$/', $res . '$0', $filename );
		if ( ! file_exists( get_theme_file_path( $high_res_name ) ) ) {
			continue;
		}
		$srcset[ $key ] = esc_url( Path\cache_buster( $high_res_name ) );
	}
	$srcset_string_arr = array();
	foreach ( $srcset as $key => $url ) {
		$srcset_string_arr[] = $url . ' ' . $key;
	}
	$srcset_string = implode( ',', $srcset_string_arr );
	return $srcset_string ? 'srcset="' . $srcset_string . '"' : '';
}

/**
 * Array to html tag.
 *
 * @example ['alt' => 'text', 'loading' => 'lazy'] -> 'alt="text" loading="lazy"'
 *
 * @param array  $attrs .
 * @param string $spacer .
 * @return string
 */
function array_to_attr_string( $attrs = array(), $spacer = ' ' ): string {

	$attr_string = '';

	foreach ( $attrs as $key => $attr ) {
		$attr_string .= esc_attr( $key ) . '="' . esc_attr( $attr ) . '"';
		if ( end( $attrs ) !== $attr ) {
			$attr_string .= ' ';
		}
	}

	return $attr_string ? $spacer . $attr_string : '';
}


/**
 * Additional wp_kses_allowed_html target
 *
 * @param string $context .
 * @return array
 */
function kses_post_extend( $context = 'post' ) {

	$wp_allowed_html = wp_kses_allowed_html( $context );

	$wp_allowed_html['img']['fetchpriority'] = true;

	return array_merge(
		$wp_allowed_html,
		array(
			'source'  => array(
				'media'  => true,
				'sizes'  => true,
				'src'    => true,
				'srcset' => true,
				'type'   => true,
			),
			'picture' => array(
				'class'  => true,
				'id'     => true,
				'media'  => true,
				'srcset' => true,
				'type'   => true,
			),
		)
	);
}
