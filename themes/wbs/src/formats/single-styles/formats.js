import { __ } from '@wordpress/i18n';

export const formats = [
	{
		name: 'wbs-format/small-text',
		title: __( '小テキスト', 'wbs' ),
		tagName: 'small',
		className: 'has-style-small-text',
		icon: 'editor-textcolor',
	},
	{
		name: 'wbs-format/text-auto-phrase',
		title: __( '改行: オートフレーズ', 'wbs' ),
		tagName: 'span',
		className: 'has-style-text-auto-phrase',
		icon: 'text',
	},
	{
		name: 'wbs-format/text-balance',
		title: __( '改行: バランス', 'wbs' ),
		tagName: 'span',
		className: 'has-style-text-balance',
		icon: 'text',
	},
];
