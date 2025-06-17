import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { getBlockWrapperAttributes } from './getBlockWrapperAttributes';

export default function save( { attributes } ) {
	const wrapperAttributes = getBlockWrapperAttributes( attributes );
	const blockProps = useBlockProps.save( { ...wrapperAttributes } );

	return (
		<div { ...blockProps }>
			<InnerBlocks.Content />
		</div>
	);
}
