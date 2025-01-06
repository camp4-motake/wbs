import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const blockProps = useBlockProps.save( {
		className: 'grid-item-block',
		style: {
			gridColumn: `span ${ attributes.columnSpan }`,
			gridRow: `span ${ attributes.rowSpan }`,
			minHeight: attributes.minHeight,
			display: 'grid',
		},
	} );

	return (
		<div { ...blockProps }>
			<InnerBlocks.Content />
		</div>
	);
}
