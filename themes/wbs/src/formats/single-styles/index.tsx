import { RichTextToolbarButton } from '@wordpress/block-editor';
import { toggleFormat } from '@wordpress/rich-text';
import { registerThemeFormat } from '../registerThemeFormat';
import type { FormatEditProps } from '../registerThemeFormat';
import { formats } from './formats';

/**
 * Formatting Toolbar add custom style
 * @see https://developer.wordpress.org/block-editor/how-to-guides/format-api/
 */

for ( const fmt of formats ) {
	const { name, title, tagName, className, icon } = fmt;

	registerThemeFormat( name, {
		title,
		tagName,
		className,
		edit: ( { isActive, onChange, value }: FormatEditProps ) => {
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
