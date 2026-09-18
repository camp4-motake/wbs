import ServerSideRender from '@wordpress/server-side-render';
import type { BlockEditPropsWithName } from '../../shared/types';
import './editor.css';

type EditProps = BlockEditPropsWithName;

export default function Edit( { name, attributes }: EditProps ) {
	return <ServerSideRender block={ name } attributes={ attributes } />;
}
