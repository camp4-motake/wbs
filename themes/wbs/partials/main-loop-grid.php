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
<div class="archive-articles <?php echo esc_attr( $class_name ); ?>">
	<div class="archive-grid">
		<?php

		while ( have_posts() ) :
			the_post();

			// post type content card
			get_template_part( 'partials/content', $ctx['template_slug'] );
		endwhile;

		if ( ! have_posts() ) {
			get_template_part( 'partials/no-posts' );
		}

		?>
	</div>
</div>

<?php get_template_part( 'partials/pagination' ); ?>
