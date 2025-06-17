<?php

/**
 * no post text
 *
 * @package wbs
 */

$ctx = wp_parse_args(
	$args,
	array(
		'no_post_label' => 'coming soon',
		'class'         => '',
	)
);

$wrapper_class_name = 'no-posts ';
if ( ! empty( $ctx['class'] ) ) {
	$wrapper_class_name .= ' ' . $ctx['class'];
}

?>
<div class="<?php echo esc_attr( $wrapper_class_name ); ?>">
	<small class="roundLabel"><?php echo esc_html( $ctx['no_post_label'] ); ?></small>
</div>
