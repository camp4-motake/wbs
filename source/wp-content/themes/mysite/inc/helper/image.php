<?php

/**
 * Image Helper
 *
 * @package wbs
 */

namespace Site\Theme\Helper\Image;

use Site\Theme\Helper\Path;

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
		return;
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
 * @return array
 */
function kses_svg() {
	$kses_defaults = wp_kses_allowed_html( 'post' );
	$svg_args      = array(
		'svg'   => array(
			'class'           => true,
			'aria-hidden'     => true,
			'aria-labelledby' => true,
			'role'            => true,
			'xmlns'           => true,
			'width'           => true,
			'height'          => true,
			'viewbox'         => true, // <= Must be lower case!
		),
		'g'     => array( 'fill' => true ),
		'title' => array( 'title' => true ),
		'path'  => array(
			'd'    => true,
			'fill' => true,
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
 * @param string  $path path.
 * @param array   $attrs attr.
 * @param boolean $is_origin use old image format.
 * @return string
 */
function auto_img( $path = '', $attrs = array(), $is_origin = true ) {
	if ( ! $path ) {
		return '';
	}

	$order_patterns = array(
		'png'  => array( 'avif', 'webp', 'png' ),
		'jpg'  => array( 'avif', 'webp', 'jpg' ),
		'webp' => array( 'avif', 'webp' ),
	);

	$info    = pathinfo( $path );
	$ext     = $info['extension'];
	$img_tag = make_img_tag( $path, $attrs );

	if ( empty( $order_patterns[ $ext ] ) ) {
		return $img_tag;
	}

	$src_items = array_map(
		function ( $e ) use ( $path, $ext, $img_tag, $is_origin ) {
			$target_path = str_replace( ".{$ext}", ".{$e}", $path );

			if ( $ext === $e && ! $is_origin ) {
				return null;
			}
			if ( file_exists( get_theme_file_path( $target_path ) ) ) {
				return $target_path;
			}
		},
		$order_patterns[ $ext ]
	);

	$src_items = array_filter( $src_items );

	$tag = '';
	foreach ( $src_items as $item ) {
		if ( end( $src_items ) === $item ) {
			$tag .= make_img_tag( $item, $attrs );
		} else {
			$tag .= make_source_tag( $item );
		}
	}

	if ( count( $src_items ) <= 1 ) {
		return $tag;
	}

	return "<picture>\n" . $tag . "</picture>\n";
}

/**
 * Image Helper
 *
 * @example single image
 * echo Image\mq_picture($src = 'images/sample.png', $img_attrs = [], $picture_attrs = []);
 *
 * @example responsive image
 * $src = [
 *  'img' => 'images/sample.jpg',
 *   'source' => [
 *     ['src' => 'images/sample-lg.jpg', 'media' => MQ_LG],
 *     ['src' => 'images/sample-sm.jpg', 'media' => MQ_MD],
 *   ]
 * ];
 * echo Image\mq_picture($src, $img_attrs = [], $picture_attrs = []);
 *
 * @param string|array $src_path image path.
 * @param array        $img_attrs image attr.
 * @param array        $picture_attrs picture attr.
 * @return string
 */
function picture_media( $src_path = '' || array(), $img_attrs = array(), $picture_attrs = array() ): string {
	$sources = false;

	if ( is_array( $src_path ) && isset( $src_path['img'] ) ) {
		$path = $src_path['img'];
		if ( isset( $src_path['source'] ) && count( $src_path['source'] ) ) {
			$sources = $src_path['source'];
		}
	} elseif ( is_string( $src_path ) ) {
		$path = $src_path;
	} else {
		return '';
	}

	$img_tag   = make_img_tag( $path, $img_attrs );
	$webp_path = preg_replace( '/\.[^.]+$/', '.webp', $path );

	if ( ! $sources && ! file_exists( get_theme_file_path( $webp_path ) ) ) {
		return $img_tag;
	}

	$n    = "\n";
	$html = '<picture' . array_to_attr_string( $picture_attrs ) . '>' . $n;

	if ( $sources ) {
		foreach ( $sources as $source ) {
			if ( ! isset( $source['src'] ) || ! isset( $source['media'] ) ) {
				continue;
			}
			$webp_path = preg_replace( '/\.[^.]+$/', '.webp', $source['src'] );
			$html     .= make_source_tag( $webp_path, 'image/webp', $source['media'] );
		}
		foreach ( $sources as $source ) {
			if ( ! isset( $source['src'] ) || ! isset( $source['media'] ) ) {
				continue;
			}
			$html .= make_source_tag( $source['src'], null, $source['media'] );
		}
	} else {
		$html .=
			'' . make_source_tag( $webp_path, 'image/webp' ) . make_source_tag( $path );
	}

	$html .= $img_tag;
	$html .= '</picture>' . $n;

	return $html;
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
		if ( $key === '1x' && $non_1x === true ) {
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
		if ( $attr !== end( $attrs ) ) {
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
