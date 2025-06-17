import { RichTextToolbarButton } from '@wordpress/block-editor';
import { registerFormatType, toggleFormat } from '@wordpress/rich-text';
import { formats } from './formats';

/**
 * Formatting Toolbar add custom style
 * @see https://developer.wordpress.org/block-editor/how-to-guides/format-api/
 */

for ( const fmt of formats ) {
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
}
