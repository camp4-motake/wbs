import { useBlockProps } from '@wordpress/block-editor';
import ServerSideRender from '@wordpress/server-side-render';
import type { BlockEditPropsWithName } from '../../shared/types';
import './editor.css';

export type SiteFooterAttributes = {
	contentUpdated: number;
	innerContent: string;
};

type EditProps = BlockEditPropsWithName< SiteFooterAttributes >;

export default function Edit( { name, attributes }: EditProps ) {
	const blockProps = useBlockProps( { className: 'footer' } );

	return (
		<div { ...blockProps }>
			<ServerSideRender
				block={ name }
				attributes={ { ...attributes } }
				skipBlockSupportAttributes
			/>
		</div>
	);
}
