<?php

/**
 * ターム一覧（現在の投稿に紐づく public タクソノミーのターム）
 *
 * @package wbs
 *
 * @var array $args {
 *     @type string $list_class <ul> に付与するクラス名。
 *     @type string $item_class 各ターム <a> に付与するクラス名。
 * }
 */

$term_list_ctx = wp_parse_args(
	$args,
	array(
		'list_class' => '',
		'item_class' => '',
	)
);

$term_list_terms = \Site\Theme\Helper\Util\get_post_term_list();

if ( empty( $term_list_terms ) ) {
	return;
}

?>
<ul class="<?php echo esc_attr( $term_list_ctx['list_class'] ); ?>">
	<?php
	foreach ( $term_list_terms as $term_list_term ) :
		$term_list_url = get_term_link( $term_list_term );
		if ( is_wp_error( $term_list_url ) ) {
			continue;
		}
		?>
		<li>
			<a class="<?php echo esc_attr( $term_list_ctx['item_class'] ); ?>" href="<?php echo esc_url( $term_list_url ); ?>"><?php echo esc_html( $term_list_term->name ); ?></a>
		</li>
	<?php endforeach; ?>
</ul>
