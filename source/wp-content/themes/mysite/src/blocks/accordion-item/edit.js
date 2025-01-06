import {
	InnerBlocks,
	InspectorControls,
	RichText,
	useBlockProps,
} from '@wordpress/block-editor';
import { PanelBody, ToggleControl } from '@wordpress/components';
import { useInstanceId } from '@wordpress/compose';
import { useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import './editor.css';

const DEFAULT_TEMPLATE = [ [ 'core/paragraph', {} ] ];

export default function Edit( { attributes, setAttributes } ) {
	const { summaryLabel, isInitialOpen, itemID } = attributes;
	const blockProps = useBlockProps( { open: isInitialOpen } );
	const uniqueID = useInstanceId( Edit, 'accordion-item' );

	const onChangeSummaryLabel = ( newSummaryLabel ) => {
		setAttributes( { summaryLabel: newSummaryLabel } );
	};

	const onChangeInitialOpen = ( newIsInitialOpen ) =>
		setAttributes( { isInitialOpen: newIsInitialOpen } );

	useEffect( () => {
		if ( itemID !== uniqueID ) {
			setAttributes( { itemID: uniqueID } );
		}
	}, [ itemID, setAttributes, uniqueID ] );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( '初期表示', 'wbs' ) }>
					<ToggleControl
						value={ isInitialOpen }
						checked={ isInitialOpen }
						label={
							isInitialOpen
								? __( '開く', 'wbs' )
								: __( '閉じる', 'wbs' )
						}
						onChange={ onChangeInitialOpen }
						__nextHasNoMarginBottom
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<div className="accordion-summary">
					<RichText
						className={ 'accordion-item-title' }
						placeholder={ __( '見出し', 'wbs' ) }
						type="text"
						value={ summaryLabel }
						onChange={ onChangeSummaryLabel }
					/>
				</div>
				<div className="accordion-panel">
					<div className="accordion-panel__inner">
						<div className="accordion-content">
							<InnerBlocks template={ DEFAULT_TEMPLATE } />
						</div>
					</div>
				</div>
			</div>
		</>
	);
}
