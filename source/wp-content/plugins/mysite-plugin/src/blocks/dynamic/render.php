<?php
/**
 * @see https://github.com/WordPress/gutenberg/blob/trunk/docs/reference-guides/block-api/block-metadata.md#render
 */

?>
<p <?php echo wp_kses_post( get_block_wrapper_attributes() ); ?>>
	<?php esc_html_e( 'inu neko – hello from a dynamic block!', 'wbs' ); ?>
</p>

<?php

$ctx = wp_parse_args( $args, array( 'class' => '' ) );

$class_name = 'copyright';

if ( $ctx['class'] ) {
	$class_name .= ' ' . esc_attr( $ctx['class'] );
}

$start_year = 2024;
$year_num   = (int) gmdate( 'Y' );

if ( $year_num > $start_year ) {
	$year_num = $start_year . '-' . $year_num;
}

?>
<p class="<?php echo esc_attr( $class_name ); ?>">
	<small class="notranslate">&copy;<?php echo esc_html( $year_num . ' ' . __( 'corp name Co., Ltd. All Rights Reserved.', 'wbs' ) ); ?></small>
</p>
