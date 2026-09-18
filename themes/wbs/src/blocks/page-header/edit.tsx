import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';
import type { BlockEditPropsWithName } from '../../shared/types';
import './editor.css';

export type PageHeaderAttributes = {
	title: string;
	subTitle: string;
};

type EditProps = BlockEditPropsWithName< PageHeaderAttributes >;

export default function Edit( { name, attributes, setAttributes }: EditProps ) {
	const { title, subTitle } = attributes;
	const blockProps = useBlockProps();

	// フロントは render.php が描画するため、エディターはその出力をそのまま表示する。
	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Page header', 'wbs' ) }>
					<TextControl
						label={ __( 'Title', 'wbs' ) }
						help={ __(
							'Falls back to the page title when left blank',
							'wbs'
						) }
						value={ title }
						onChange={ ( value: string ) =>
							setAttributes( { title: value } )
						}
						__nextHasNoMarginBottom
					/>
					<TextControl
						label={ __( 'Sub title', 'wbs' ) }
						value={ subTitle }
						onChange={ ( value: string ) =>
							setAttributes( { subTitle: value } )
						}
						__nextHasNoMarginBottom
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<ServerSideRender
					block={ name }
					attributes={ attributes }
					skipBlockSupportAttributes
				/>
			</div>
		</>
	);
}
