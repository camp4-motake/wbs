import ServerSideRender from '@wordpress/server-side-render';
import './editor.css';

export default function Edit( { name, attributes } ) {
	return <ServerSideRender block={ name } attributes={ attributes } />;
}
