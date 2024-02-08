<?php

/**
 * main article loop
 */

global $wp_query;

$attr       = array( 'class' => 'archive-section' );
$_post_type = get_post_type();

?>
<div <?php echo wp_kses_post( get_block_wrapper_attributes( $attr ) ); ?>class="archive-section">
	<?php

	if ( 'topics' === $_post_type ) {
		get_template_part( 'parts/components/term-select-topics' );
	}

	?>
	<div class="entry-container alignWide content-grid -repeat-2 -dummy" data-post-count="<?php echo esc_attr( $wp_query->post_count ); ?>">
		<?php

		while ( have_posts() ) :
			the_post();
			get_template_part( 'parts/components/content', $_post_type );
		endwhile;

		?>
	</div>

	<?php get_template_part( 'parts/components/pagination' ); ?>
</div>
