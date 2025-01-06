import { RichTextToolbarButton } from '@wordpress/block-editor';
import { registerFormatType, toggleFormat } from '@wordpress/rich-text';

const formats = [
	/*
	// example
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
	{
		name: 'wbs-format/extra-sup-asterisk-force-small',
		title: __( '上付き注釈（小固定）', 'wbs' ),
		tagName: 'sup',
		className: 'has-style-extra-sup-asterisk-force-small',
		icon: 'text',
	},
	 */
];

/**
 * Formatting Toolbar add custom style
 * @see https://developer.wordpress.org/block-editor/how-to-guides/format-api/
 */
export const formattingToolbar = () => {
	formats?.forEach( ( fmt ) => {
		const { name, title, tagName, className, icon } = fmt;

		registerFormatType( name, {
			title,
			tagName,
			className,
			edit: ( { isActive, onChange, value } ) => {
				return (
					<RichTextToolbarButton
						icon={ icon }
						title={ title }
						onClick={ () => {
							onChange( toggleFormat( value, { type: name } ) );
						} }
						isActive={ isActive }
					/>
				);
			},
		} );
	} );
};
