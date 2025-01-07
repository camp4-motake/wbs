<?php

/**
 * main loop grid
 *
 * @package wbs
 */

$ctx = wp_parse_args(
	$args,
	array(
		'class'         => '',
		'template_slug' => get_post_type(),
	)
);

$class_name = '';
if ( $ctx['class'] ) {
	$class_name .= $ctx['class'];
}

?>
<div class="<?php echo esc_attr( $class_name ); ?>">
	<?php

	while ( have_posts() ) :
		the_post();
		get_template_part( 'template-parts/content', $ctx['template_slug'] );
	endwhile;

	if ( ! have_posts() ) {
		get_template_part( 'template-parts/no-posts' );
	}

	?>
</div>
