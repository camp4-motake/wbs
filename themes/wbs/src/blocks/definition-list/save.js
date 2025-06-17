import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import './style.css';

export default function save() {
	const blockProps = useBlockProps.save( {
		className: 'definition-list-block',
	} );

	return (
		<dl { ...blockProps }>
			<InnerBlocks.Content />
		</dl>
	);
}
