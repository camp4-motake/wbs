import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import {
	BaseControl,
	PanelBody,
	SelectControl,
	TextControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

import type { BlockEditPropsWithName } from '../../shared/types';
import { ASPECT_RATIO } from './constants';
import './editor.css';

export type EmbedIframeAttributes = {
	htmlSource: string;
	aspectRatio: string;
};

type EditProps = BlockEditPropsWithName< EmbedIframeAttributes >;

export default function Edit( { name, attributes, setAttributes }: EditProps ) {
	const { htmlSource, aspectRatio } = attributes;
	const blockProps = useBlockProps( {
		className: aspectRatio === 'fit' ? 'has-fit' : '',
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Google Map iFrame', 'wbs' ) }>
					<BaseControl
						id="htmlSource"
						label={ __( 'HTML', 'wbs' ) }
						__nextHasNoMarginBottom
						help={ __( '地図の埋め込みコード（iframe）' ) }
					>
						<TextControl
							value={ htmlSource }
							onChange={ ( val ) =>
								setAttributes( { htmlSource: val } )
							}
							__nextHasNoMarginBottom
						/>
					</BaseControl>
					<BaseControl
						id="aspectRatio"
						label={ __( 'Aspect Ratio', 'wbs' ) }
						__nextHasNoMarginBottom
						help={ __( '表示比率' ) }
					>
						<SelectControl
							value={ aspectRatio }
							onChange={ ( val ) =>
								setAttributes( { aspectRatio: val } )
							}
							options={ ASPECT_RATIO }
							__nextHasNoMarginBottom
						/>
					</BaseControl>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<ServerSideRender block={ name } attributes={ attributes } />
			</div>
		</>
	);
}
