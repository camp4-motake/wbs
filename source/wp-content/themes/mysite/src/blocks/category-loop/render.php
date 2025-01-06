<?php

/**
 * Topic Loop Section
 *
 * @package wbs
 *
 * @param array $attributes - A clean associative array of block attributes.
 * @param array $block - All the block settings and attributes.
 * @param string $content - The block inner HTML (usually empty unless using inner blocks).
 */

$_post_type = $attributes['postType'];
$_tax       = $attributes['category'];
$_mode      = $attributes['displayMode'];
$_terms     = get_terms( $_tax );

$class_name = 'mode-' . $_mode;

?>
<section <?php echo wp_kses_post( get_block_wrapper_attributes( array( 'class' => $class_name ) ) ); ?>>
	<?php

	foreach ( $_terms as $_term ) :

		$the_query = new WP_Query(
			array(
				'post_type'              => $_post_type,
				'posts_per_page'         => 50,
				'no_found_rows'          => true,
				'update_post_meta_cache' => false,
				'update_post_term_cache' => false,
				'tax_query'              => array(
					array(
						'taxonomy' => $_tax,
						'field'    => 'term_id',
						'terms'    => $_term->term_id,
					),
				),
			)
		);

		if ( ! $the_query->have_posts() ) {
			continue;
		}

		?>
		<h3 class="category-heading"><?php echo esc_html( $_term->name ); ?></h3>
		<?php if ( 'index' === $_mode ) : ?>
			<?php

			/**
			 * list loop
			 */

			?>
			<ul class="article-index-list entry-container alignWide content-grid -repeat-3">
				<?php

				while ( $the_query->have_posts() ) :
					$the_query->the_post();
					$num = 0;

					?>
					<li>
						<a href="#area-article-<?php echo esc_attr( get_the_ID() ); ?>" class="num-heading">
							<?php if ( $num ) : ?>
								<span class="badge-num"><?php echo esc_html( $num ); ?></span>
							<?php endif; ?>
							<span class="title"><?php the_title(); ?></span>
						</a>
					</li>
					<?php

				endwhile;

				?>
			</ul>
		<?php else : ?>
			<?php

			/**
			 * modal article loop
			 */

			?>
			<div class="entry-container alignWide content-grid -repeat-3" data-post-count="<?php echo esc_attr( $the_query->post_count ); ?>">
				<?php

				while ( $the_query->have_posts() ) :

					$the_query->the_post();
					get_template_part( 'template-parts/components/content', $_post_type . '-modal' );

				endwhile;

				?>
			</div>
		<?php endif; ?>
		<?php

	endforeach;

	if ( ! $_terms ) {
		get_template_part( 'template-parts/components/no-posts' );
	}

	wp_reset_postdata();

	?>
</section>
