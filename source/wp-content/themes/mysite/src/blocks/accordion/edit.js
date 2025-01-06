import {
	InnerBlocks,
	InspectorControls,
	useBlockProps,
} from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import clsx from 'clsx';
import './editor.css';

const ALLOWED_BLOCKS = [ 'wbs/accordion-item' ];

export default function Edit( { attributes, setAttributes } ) {
	const { isShadow, isSingleOpen } = attributes;
	const blockProps = useBlockProps( {
		className: clsx( { 'is-style-shadow': isShadow } ),
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( '表示設定', 'wbs' ) }>
					<ToggleControl
						value={ isShadow }
						checked={ isShadow }
						label={ __( 'ドロップシャドウ', 'wbs' ) }
						onChange={ ( newIsShadow ) =>
							setAttributes( { isShadow: newIsShadow } )
						}
						__nextHasNoMarginBottom
					/>
					<ToggleControl
						value={ isSingleOpen }
						checked={ isSingleOpen }
						label={ __(
							'グループ内の他のパネルを自動で閉じる',
							'wbs'
						) }
						onChange={ ( newIsSingeOpen ) =>
							setAttributes( { isSingleOpen: newIsSingeOpen } )
						}
						__nextHasNoMarginBottom
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<InnerBlocks
					allowedBlocks={ ALLOWED_BLOCKS }
					renderAppender={ InnerBlocks.ButtonBlockAppender }
					// template={ [ [ 'wbs/accordion-item', {} ] ] }
				/>
			</div>
		</>
	);
}
