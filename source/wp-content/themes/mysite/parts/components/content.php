<?php

/**
 * Topics article content
 *
 * @package wbs
 */

use Site\Theme\Helper\Content;

$date_string = get_field( 'date_string' );

?>
<a href="<?php the_permalink(); ?>" class="content-topics">
	<?php echo wp_kses_post( Content\new_badge_display() ); ?>
	<div class="content-card">
		<figure class="content-thumbnail"><?php the_post_thumbnail( 'large' ); ?></figure>
		<div class="content-body">
			<div class="content-categories">
				<?php get_template_part( 'parts/components/loop-badge-category' ); ?>
			</div>
			<h3 class="content-heading"><?php the_title(); ?></h3>
			<?php if ( $date_string ) : ?>
				<div class="content-date-field"><?php echo esc_html( $date_string ); ?></div>
			<?php endif; ?>
		</div>
	</div>
	<div class="content-updated-label"><?php get_template_part( 'parts/components/entry-meta' ); ?></div>
</a>
