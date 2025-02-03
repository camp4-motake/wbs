<?php

$ctx = wp_parse_args(
	$args,
	array(
		'post_type'     => 'materials',
		'tax'           => 'category-materials',
		'class'         => '',
		'is_hide_empty' => false,
	)
);

$_tax_slug = get_query_var( $ctx['tax'] );
$_terms    = get_terms( $ctx['tax'] );

if ( ! $_terms && $ctx['is_hide_empty'] ) {
	return;
}

$class_name = 'term-tab-links';
if ( $ctx['class'] ) {
	$class_name .= ' ' . $ctx['class'];
}

?>
<nav class="<?php echo esc_attr( $class_name ); ?>">
	<div class="term-tab-links__container">
		<a
			href="<?php echo esc_url( get_post_type_archive_link( $ctx['post_type'] ) ); ?>"
			class="term-tab-link"><?php echo esc_html( __( 'すべて' ) ); ?></a>
		<?php

		foreach ( $_terms as $_term ) :
			?>
			<a
				href="<?php echo esc_url( get_term_link( $_term ) ); ?>"
				class="term-tab-link"><?php echo esc_html( $_term->name ); ?></a>
			<?php
		endforeach;

		?>
	</div>
</nav>