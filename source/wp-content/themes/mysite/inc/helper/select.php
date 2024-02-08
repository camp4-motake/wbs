<?php

namespace Site\Theme\Helper\Select;

function kses_select() {
	return array(
		'select' => array(
			'class'    => true,
			'onChange' => true,
		),
		'option' => array(
			'class'    => true,
			'disabled' => true,
			'selected' => true,
		),
	);
}

/**
 * ターム一覧のセレクトボックス出力
 *
 * @param string $label デフォルトラベル
 * @param string $tax = タクソノミー
 * @return string html
 */
function get_dropdown_taxonomy(
	$tax = '',
	$label = '- カテゴリーを選択 -',
	$inject_first = ''
) {
	$html  = '';
	$terms = get_terms( $tax );

	if ( ! empty( $terms ) ) {
		$html .= '<select onChange="window.location.href=this.options[this.selectedIndex].value;">' . "\n";
		$html .= $inject_first;
		$html .= '<option disabled selected>' . $label . '</option>' . "\n";

		foreach ( $terms as $term ) {
			// if ($term->parent === 0) {
			// continue;
			// }
			$html .= sprintf(
				'<option value="%s">%s</option>' . "\n",
				get_term_link( $term->term_id, $tax ),
				esc_attr( $term->name )
			);
		}

		$html .= '</select>';
	}

	return $html;
}

/**
 *  月別アーカイブのセレクトボックス出力
 */
function get_dropdown_yearly( $post_type = 'news', $type = 'yearly' ) {
	$option = wp_get_archives(
		array(
			'echo'      => false,
			'format'    => 'option',
			'post_type' => $post_type,
			'type'      => $type,
		)
	);

	$html = '';

	if ( $option ) {
		$html .= '<select onChange="document.location.href=this.options[this.selectedIndex].value;">';
		$html .= '<option value="">- ' . esc_attr__( 'Select Year', 'wbs' ) . ' -</option>';
		$html .= $option;
		$html .= '</select>';
	}

	return $html;
}
