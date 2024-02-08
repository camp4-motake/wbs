<?php

/**
 * Content Single topics
 *
 * @package wbs
 */

$date_string     = get_field( 'date_string' );
$post_type_label = __( '一覧', 'wbs' );

if ( get_post_type_object( get_post_type() ) ) {
	$post_type_label = get_post_type_object( get_post_type() )->label . $post_type_label;
}

?>
<article class="single-post-wrapper">
	<header class="single-post-header entry-container">

		<h1 class="single-heading"><?php the_title(); ?></h1>

		<div class="single-post-header-meta">
			<?php if ( $date_string ) : ?>
				<div class="content-date-field"><?php echo esc_html( $date_string ); ?></div>
			<?php else : ?>
				<div></div>
			<?php endif; ?>

			<div class="content-categories">
				<?php get_template_part( 'parts/components/loop-badge-datetype' ); ?>
				<?php get_template_part( 'parts/components/loop-badge-category' ); ?>
			</div>
		</div>

		<?php if ( has_post_thumbnail() ) : ?>
			<figure class="content-thumbnail"><?php the_post_thumbnail( 'full' ); ?></figure>
		<?php endif; ?>

		<div class="content-updated-label"><?php get_template_part( 'parts/components/entry-meta' ); ?></div>
	</header>

	<div class="single-post-content entry-content">
		<?php the_content(); ?>
	</div>

	<footer class="single-post-footer entry-container">
		<?php get_template_part( 'parts/components/share-link' ); ?>
		<?php get_template_part( 'parts/components/btn-post-type-ink', null, array( 'label' => $post_type_label ) ); ?>
	</footer>
</article>
