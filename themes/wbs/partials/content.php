<?php

$ctx = wp_parse_args( $args, array( 'class' => '' ) );

$wrapper_class_name = 'card-article';

if ( ! empty( $ctx['class'] ) ) {
	$wrapper_class_name .= ' ' . $ctx['class'];
}

?>
<article class="<?php echo esc_attr( $wrapper_class_name ); ?>">
	<figure class="card-thumbnail"><?php the_post_thumbnail(); ?></figure>
	<div class="card-inner">
		<div class="card-head">
			<?php get_template_part( 'partials/entry-meta' ); ?>
			<div class="card-tag-list">
				<?php get_template_part( 'partials/loop-badge-category' ); ?>
			</div>
		</div>
		<h3 class="card-heading">
			<a href="<?php the_permalink(); ?>" class="card-link"><?php the_title(); ?></a>
		</h3>
	</div>
</article>