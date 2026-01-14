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

export interface StyleOption {
	label: string;
	value: string;
}

export interface ExtendBlockStylesConfig {
	blockNames: string | string[];
	styleOptions?: StyleOption[];
	panelTitle?: string;
	panelDescription?: string;
	initialOpen?: boolean;
	namespace?: string;
	selectionType?: 'checkbox' | 'radio' | 'select';
	defaultStyle?: string;
}

/**
 * Registers a function to add custom block styles to WordPress blocks.
 *
 * @param {ExtendBlockStylesConfig} config Configuration object
 */
export const registerExtendBlockStyles = ( {
	blockNames,
	styleOptions = [],
	panelTitle = __( 'スタイル設定', 'wbs' ),
	panelDescription = '',
	initialOpen = true,
	namespace = 'wbs',
	selectionType = 'checkbox',
	defaultStyle = '',
}: ExtendBlockStylesConfig ) => {
	// Convert string to array if needed
	const targetBlocks = Array.isArray( blockNames )
		? blockNames
		: [ blockNames ];

	// Store the values of the styleOptions
	const styleOptionValues = styleOptions.map( ( option ) => option.value );

	// Extend block attributes
	const extendBlockAttributes = ( settings: any, name: string ) => {
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
		existingClassName: string = '',
		newStyles: string | string[]
	) => {
		const existingClasses = existingClassName
			.split( ' ' )
			.filter( Boolean );

		const preservedClasses = existingClasses.filter(
			( className ) => ! styleOptionValues.includes( className )
		);

		if ( selectionType === 'checkbox' && Array.isArray( newStyles ) ) {
			return [ ...preservedClasses, ...newStyles ].join( ' ' );
		}
		return [ ...preservedClasses, newStyles as string ]
			.filter( Boolean )
			.join( ' ' );
	};

	// Style settings component
	const StyleSettings = ( {
		attributes,
		setAttributes,
	}: {
		attributes: any;
		setAttributes: ( attrs: any ) => void;
	} ) => {
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
					.find( ( cls: string ) =>
						styleOptionValues.includes( cls )
					) || defaultStyle;
			setSelectedStyle( currentSelectedStyle );
		}, [ className ] );

		const updateStyles = ( newStyle: string | string[] ) => {
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
							{ label: __( 'Default', 'wbs' ), value: '' },
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
							{ label: __( 'Default', 'wbs' ), value: '' },
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
						onChange={ ( checked: boolean ) => {
							const newStyles = checked
								? [ ...customStyles, option.value ]
								: customStyles.filter(
										( style: string ) =>
											style !== option.value
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
		return ( props: any ) => {
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
