import { InspectorControls } from '@wordpress/block-editor';
import {
	CheckboxControl,
	PanelBody,
	RadioControl,
	SelectControl,
} from '@wordpress/components';
import { createHigherOrderComponent } from '@wordpress/compose';
import { Fragment, useEffect, useState } from '@wordpress/element';
import { addFilter } from '@wordpress/hooks';
import { __ } from '@wordpress/i18n';

/**
 * Registers a function to add custom block styles to WordPress blocks.
 *
 * @param {Object}             config                  Configuration object
 * @param {string|string[]}    config.blockNames       Name(s) of the target block(s)
 * @param {Object[]}           config.styleOptions     Array of style options
 * @param {string}             config.panelTitle       Title of the sidebar panel
 * @param {string}             config.panelDescription Optional description text to display under the panel title
 * @param {boolean}            config.initialOpen      Initial state of the panel (open or closed)
 * @param {string}             config.namespace        Namespace for the filter
 * @param {'checkbox'|'radio'} config.selectionType    Type of selection control ('checkbox' or 'radio')
 * @param {string}             [config.defaultStyle]   Default selected style for radio buttons
 */
export const registerExtendBlockStyles = ( {
	blockNames,
	styleOptions = [],
	panelTitle = __( 'スタイル設定', 'hmj' ),
	panelDescription = '',
	initialOpen = true,
	namespace = 'hmj',
	selectionType = 'checkbox',
	defaultStyle = '',
} = {} ) => {
	// Convert string to array if needed
	const targetBlocks = Array.isArray( blockNames )
		? blockNames
		: [ blockNames ];

	// Store the values of the styleOptions
	const styleOptionValues = styleOptions.map( ( option ) => option.value );

	// Extend block attributes
	const extendBlockAttributes = ( settings, name ) => {
		if ( targetBlocks.includes( name ) ) {
			return {
				...settings,
				attributes: {
					...settings.attributes,
					customStyles: {
						type: selectionType === 'checkbox' ? 'array' : 'string',
						default:
							selectionType === 'checkbox' ? [] : defaultStyle,
					},
				},
			};
		}
		return settings;
	};

	// Update className while preserving existing classes
	const updateClassNameWithPreservation = (
		existingClassName = '',
		newStyles
	) => {
		const existingClasses = existingClassName
			.split( ' ' )
			.filter( Boolean );

		const preservedClasses = existingClasses.filter(
			( className ) => ! styleOptionValues.includes( className )
		);

		if ( selectionType === 'checkbox' ) {
			return [ ...preservedClasses, ...newStyles ].join( ' ' );
		}
		return [ ...preservedClasses, newStyles ].filter( Boolean ).join( ' ' );
	};

	// Style settings component
	const StyleSettings = ( { attributes, setAttributes } ) => {
		const {
			customStyles = selectionType === 'checkbox' ? [] : defaultStyle,
			className = '',
		} = attributes;

		const [ selectedStyle, setSelectedStyle ] = useState( customStyles );

		useEffect( () => {
			// Ensure selectedStyle matches the existing className
			const currentSelectedStyle =
				className
					.split( ' ' )
					.find( ( cls ) => styleOptionValues.includes( cls ) ) ||
				defaultStyle;
			setSelectedStyle( currentSelectedStyle );
		}, [ setSelectedStyle, className ] );

		const updateStyles = ( newStyle ) => {
			const updatedClassName = updateClassNameWithPreservation(
				className,
				newStyle
			);

			setAttributes( {
				customStyles: newStyle,
				className: updatedClassName,
			} );
			setSelectedStyle( newStyle );
		};

		if ( selectionType === 'radio' ) {
			return (
				<PanelBody title={ panelTitle } initialOpen={ initialOpen }>
					{ panelDescription && (
						<p className="block-editor-panel-color-gradient__description">
							{ panelDescription }
						</p>
					) }
					<RadioControl
						selected={ selectedStyle }
						options={ [
							{ label: __( 'Default', 'hmj' ), value: '' },
							...styleOptions.map( ( option ) => ( {
								label: option.label,
								value: option.value,
							} ) ),
						] }
						onChange={ updateStyles }
					/>
				</PanelBody>
			);
		}

		if ( selectionType === 'select' ) {
			return (
				<PanelBody title={ panelTitle } initialOpen={ initialOpen }>
					{ panelDescription && (
						<p className="block-editor-panel-color-gradient__description">
							{ panelDescription }
						</p>
					) }
					<SelectControl
						value={ selectedStyle }
						options={ [
							{ label: __( 'Default', 'hmj' ), value: '' },
							...styleOptions.map( ( option ) => ( {
								label: option.label,
								value: option.value,
							} ) ),
						] }
						onChange={ updateStyles }
						__nextHasNoMarginBottom
					/>
				</PanelBody>
			);
		}

		return (
			<PanelBody title={ panelTitle } initialOpen={ initialOpen }>
				{ panelDescription && (
					<p className="block-editor-panel-color-gradient__description">
						{ panelDescription }
					</p>
				) }
				{ styleOptions.map( ( option ) => (
					<CheckboxControl
						key={ option.value }
						label={ option.label }
						checked={ customStyles.includes( option.value ) }
						onChange={ ( checked ) => {
							const newStyles = checked
								? [ ...customStyles, option.value ]
								: customStyles.filter(
										( style ) => style !== option.value
								  );
							updateStyles( newStyles );
						} }
					/>
				) ) }
			</PanelBody>
		);
	};

	// Extend editor UI
	const withCustomStyles = createHigherOrderComponent( ( BlockEdit ) => {
		return ( props ) => {
			if ( ! targetBlocks.includes( props.name ) ) {
				return <BlockEdit { ...props } />;
			}

			return (
				<Fragment>
					<BlockEdit { ...props } />
					<InspectorControls>
						<StyleSettings { ...props } />
					</InspectorControls>
				</Fragment>
			);
		};
	}, 'withCustomStyles' );

	// Register filters
	addFilter(
		'blocks.registerBlockType',
		`${ namespace }/style-attributes`,
		extendBlockAttributes
	);

	addFilter(
		'editor.BlockEdit',
		`${ namespace }/with-custom-styles`,
		withCustomStyles
	);
};
