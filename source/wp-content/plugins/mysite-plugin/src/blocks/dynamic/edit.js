import { useBlockProps } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import './editor.css';

export default function Edit( { name, attributes } ) {
	const blockProps = useBlockProps();

	return (
		<div { ...blockProps }>
			<ServerSideRender block={ name } attributes={ attributes } />
		</div>
	);
}
