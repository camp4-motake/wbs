<?php

$ctx = wp_parse_args(
	$args,
	array(
		'taxonomy'       => 'category-news',
		'is_link'        => false,
		'is_theme_color' => true,
	)
);

$terms = wp_get_post_terms( get_the_ID(), $ctx['taxonomy'] );
if ( ! $terms || ! empty( $terms->errors ) ) {
	return;
}

$class_name = '';
if ( ! empty( $ctx['class_name'] ) ) {
	$class_name = ' ' . $ctx['class_name'];
}

foreach ( $terms as $term_item ) :
	$badge_class = 'badge-round-text';

	if ( $ctx['is_theme_color'] ) {
		$color_theme_key = get_term_meta( $term_item->term_id, '_meta_term_color_theme', true );

		if ( ! empty( $color_theme_key ) ) {
			$badge_class .= ' has-theme-color-' . $color_theme_key;
		}
	}

	if ( $ctx['is_link'] ) :
		?>
		<a
			href="<?php echo esc_url( get_term_link( $term_item ) ); ?>"
			class="<?php echo esc_attr( $badge_class ); ?>"><?php echo esc_html( $term_item->name ); ?></a>
		<?php
	else :
		?>
		<span class="<?php echo esc_attr( $badge_class ); ?>"><?php echo esc_html( $term_item->name ); ?></span>
		<?php
	endif;

	?>
	<?php

endforeach;

?>