<?php

$ctx = wp_parse_args(
	$args,
	array(
		'class' => '',
	)
);

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
	<small class="notranslate">&copy;<?php echo esc_html( $year_num . ' ' . __( 'CORP_NAME. All Rights Reserved.', 'wbs' ) ); ?></small>
</p>
