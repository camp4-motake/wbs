import { InnerBlocks, RichText, useBlockProps } from '@wordpress/block-editor';
import './style.css';

export default function save( { attributes } ) {
	const { term } = attributes;
	const blockProps = useBlockProps.save();

	return (
		<div { ...blockProps }>
			<dt>
				<RichText.Content tagName="span" value={ term } />
			</dt>
			<dd>
				<InnerBlocks.Content />
			</dd>
		</div>
	);
}
