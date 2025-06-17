<?php

/**
 * iframe display
 *
 * @package wbs
 *
 * @param array $attributes - A clean associative array of block attributes.
 * @param array $block - All the block settings and attributes.
 * @param string $content - The block inner HTML (usually empty unless using inner blocks).
 */

$source = $attributes['htmlSource'];
$ratio  = $attributes['aspectRatio'];

$tags           = array();
$tags['iframe'] = array(
	'src'             => true,
	'height'          => true,
	'width'           => true,
	'frameborder'     => true,
	'allowfullscreen' => true,
	'loading'         => true,
);

$wrapper_attributes = array();

if ( empty( $ratio ) ) {
	$ratio = '4/3';
}

if ( 'fit' === $ratio ) {
	$ratio              = 'none';
	$wrapper_attributes = array_merge( $wrapper_attributes, array( 'class' => 'has-fit' ) );
}

?>
<div <?php echo wp_kses_post( get_block_wrapper_attributes( $wrapper_attributes ) ); ?>>
	<div class="embed-source" style="--aspect-ratio:<?php echo esc_attr( $ratio ); ?>;">
		<?php

		if ( ! empty( $source ) ) {
			echo wp_kses( $source, $tags );
		}

		?>
	</div>
</div>
