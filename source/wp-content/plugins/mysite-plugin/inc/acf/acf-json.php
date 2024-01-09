<?php

namespace MySite\Plugin\Acf\Json;

add_filter( 'acf/settings/save_json', __NAMESPACE__ . '\save_acf_json' );
add_filter( 'acf/settings/load_json', __NAMESPACE__ . '\load_acf_json' );

function save_acf_json( $path ) {
	return __DIR__ . '/../acf-json';
}

function load_acf_json( $paths ) {
	unset( $paths[0] );
	$paths[] = __DIR__ . '/../acf-json';

	return $paths;
}
