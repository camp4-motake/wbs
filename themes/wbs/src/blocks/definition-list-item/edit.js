import { InnerBlocks, RichText, useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import './editor.css';

const ALLOWED_BLOCKS = [
	'core/buttons',
	'core/heading',
	'core/image',
	'core/list',
	'core/paragraph',
];
const TEMPLATE = [ [ 'core/paragraph' ] ];

export default function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps();
	const { term } = attributes;

	return (
		<div { ...blockProps }>
			<dt>
				<RichText
					tagName="span"
					value={ term }
					onChange={ ( newTerm ) =>
						setAttributes( { term: newTerm } )
					}
					placeholder={ __( '見出し', 'wbs' ) }
				/>
			</dt>
			<dd>
				<InnerBlocks
					allowedBlocks={ ALLOWED_BLOCKS }
					template={ TEMPLATE }
				/>
			</dd>
		</div>
	);
}
