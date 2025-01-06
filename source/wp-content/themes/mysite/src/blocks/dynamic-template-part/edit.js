import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { BaseControl, PanelBody, TextControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';
import './editor.css';

export default function Edit( { name, attributes, setAttributes } ) {
	const { templatePartPath } = attributes;
	const blockProps = useBlockProps();

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Template', 'wbs' ) }>
					<BaseControl
						id="mapSource"
						label={ __( 'templatePartPath', 'wbs' ) }
						help={ __(
							'Automatic selection if left blank',
							'wbs'
						) }
						__nextHasNoMarginBottom
					>
						<TextControl
							value={ templatePartPath }
							onChange={ ( newTemplatePartPath ) =>
								setAttributes( {
									templatePartPath: newTemplatePartPath,
								} )
							}
							__nextHasNoMarginBottom
						/>
					</BaseControl>
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
