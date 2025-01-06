import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import './editor.css';

export default function Edit( { attributes } ) {
	const {} = attributes;
	const blockProps = useBlockProps( {
		className: 'swiper-slide',
	} );

	return (
		<div { ...blockProps }>
			<InnerBlocks />
		</div>
	);
}
