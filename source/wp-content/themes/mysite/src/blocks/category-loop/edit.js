import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { BaseControl, PanelBody, SelectControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

import { CATEGORY, DISPLAY_MODE, POST_TYPE } from './constants';
import './editor.css';

export default function Edit( { name, attributes, setAttributes } ) {
	const blockProps = useBlockProps();

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Category Loop', 'wbs' ) }>
					<BaseControl
						id="postType"
						label={ __( 'PostType', 'wbs' ) }
						help={ __( '投稿タイプ' ) }
					>
						<SelectControl
							value={ attributes.postType }
							onChange={ ( val ) =>
								setAttributes( { postType: val } )
							}
							options={ POST_TYPE }
						/>
					</BaseControl>
					<BaseControl
						id="category"
						label={ __( 'Category', 'wbs' ) }
						help={ __( 'カテゴリー' ) }
					>
						<SelectControl
							value={ attributes.category }
							onChange={ ( val ) =>
								setAttributes( { category: val } )
							}
							options={ CATEGORY }
						/>
					</BaseControl>
					<BaseControl
						id="mode"
						label={ __( 'Mode', 'wbs' ) }
						help={ __( '表示モード' ) }
					>
						<SelectControl
							value={ attributes.displayMode }
							onChange={ ( val ) =>
								setAttributes( { displayMode: val } )
							}
							options={ DISPLAY_MODE }
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
