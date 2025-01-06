import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import './editor.css';

const ALLOWED_BLOCKS = [ 'wbs/definition-list-item' ];
const TEMPLATE = [ [ 'wbs/definition-list-item' ] ];

export default function Edit() {
	const blockProps = useBlockProps( {
		className: 'definition-list-block',
	} );

	return (
		<dl { ...blockProps }>
			<InnerBlocks
				allowedBlocks={ ALLOWED_BLOCKS }
				template={ TEMPLATE }
				renderAppender={ InnerBlocks.ButtonBlockAppender }
			/>
		</dl>
	);
}
