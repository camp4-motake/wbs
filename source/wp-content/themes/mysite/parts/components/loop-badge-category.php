<?php

$ctx = wp_parse_args(
	$args,
	array(
		'taxonomy' => 'category-topics',
		'is_link'  => false,
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

	if ( $ctx['is_link'] ) {

		?>
		<a href="<?php echo esc_url( get_term_link( $term_item ) ); ?>" class="badge-block-text"><?php echo esc_html( $term_item->name ); ?></a>
		<?php

	} else {

		?>
		<span class="badge-block-text"><?php echo esc_html( $term_item->name ); ?></span>
		<?php

	}

endforeach;
