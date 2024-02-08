<?php

$ctx = wp_parse_args(
	$args,
	array(
		'post_type' => 'topics',
		'tax'       => 'category-topics',
	)
);

$_tax_slug = get_query_var( $ctx['tax'] );
$_terms    = get_terms( $ctx['tax'] );

if ( ! $_terms ) {
	return;
}

?>
<select onChange="window.location.href=this.options[this.selectedIndex].value;">
	<option value="<?php echo esc_url( get_post_type_archive_link( $ctx['post_type'] ) ); ?>"><?php esc_html_e( 'ALL', 'wbs' ); ?></option>
	<?php

	foreach ( $_terms as $_term ) {
		$selected = '';

		if ( is_tax( $ctx['tax'] ) && $_tax_slug && $_tax_slug === $_term->slug ) {
			$selected = ' selected';
		}

		printf(
			'<option value="%s"%s>%s</option>' . "\n",
			esc_html( get_term_link( $_term->term_id, $ctx['tax'] ) ),
			esc_attr( $selected ),
			esc_attr( $_term->name )
		);
	}

	?>
</select>
