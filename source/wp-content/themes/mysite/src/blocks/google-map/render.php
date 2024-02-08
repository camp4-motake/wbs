<?php

/**
 * Google Map display
 *
 * @package wbs
 *
 * @param array $attributes - A clean associative array of block attributes.
 * @param array $block - All the block settings and attributes.
 * @param string $content - The block inner HTML (usually empty unless using inner blocks).
 */

$source = $attributes['mapSource'];
$ratio  = $attributes['aspectRatio'];

if ( empty( $ratio ) ) {
	$ratio = '4/3';
}

$tags           = array();
$tags['iframe'] = array(
	'src'             => true,
	'height'          => true,
	'width'           => true,
	'frameborder'     => true,
	'allowfullscreen' => true,
	'loading'         => true,
);

?>
<div <?php echo wp_kses_post( get_block_wrapper_attributes() ); ?>>
	<div class="map" style="--aspect-ratio:<?php echo esc_attr( $ratio ); ?>;">
		<?php

		if ( ! empty( $source ) ) {
			echo wp_kses( $source, $tags );
		}

		?>
	</div>
</div>
