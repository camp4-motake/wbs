import {
	InnerBlocks,
	InspectorControls,
	useBlockProps,
} from '@wordpress/block-editor';
import {
	PanelBody,
	TextControl,
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalUnitControl as UnitControl,
} from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import './editor.css';

export default function Edit( { attributes, setAttributes } ) {
	const blockProps = useBlockProps( {
		className: 'grid-item-block',
		style: {
			gridColumn: `span ${ attributes.columnSpan }`,
			gridRow: `span ${ attributes.rowSpan }`,
			minHeight: attributes.minHeight,
			display: 'grid',
		},
	} );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'Grid Item Settings' ) }>
					<TextControl
						type="number"
						label={ __( 'Column Span' ) }
						value={ attributes.columnSpan }
						onChange={ ( value ) =>
							setAttributes( { columnSpan: parseInt( value ) } )
						}
						min={ 1 }
						max={ 12 }
						__nextHasNoMarginBottom
					/>
					<TextControl
						type="number"
						label={ __( 'Row Span' ) }
						value={ attributes.rowSpan }
						onChange={ ( value ) =>
							setAttributes( { rowSpan: parseInt( value ) } )
						}
						min={ 1 }
						max={ 12 }
						__nextHasNoMarginBottom
					/>
					<UnitControl
						label={ __( 'Minimum Height' ) }
						value={ attributes.minHeight }
						onChange={ ( value ) =>
							setAttributes( { minHeight: value } )
						}
						units={ [
							{ value: 'px', label: 'px' },
							{ value: 'rem', label: 'rem' },
							{ value: 'em', label: 'em' },
							{ value: 'vh', label: 'vh' },
							{ value: 'auto', label: 'auto' },
						] }
					/>
				</PanelBody>
			</InspectorControls>
			<div { ...blockProps }>
				<InnerBlocks />
			</div>
		</>
	);
}
