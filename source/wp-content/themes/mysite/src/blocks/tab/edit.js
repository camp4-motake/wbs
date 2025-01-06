import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { TextControl } from '@wordpress/components';
import { dispatch, select } from '@wordpress/data';
import { useEffect } from '@wordpress/element';
import './editor.css';

export default function Edit( { attributes, clientId, setAttributes } ) {
	const { tabLabel, blockIndex } = attributes;
	const editor = select( 'core/block-editor' );
	const parentBlockID = editor.getBlockParentsByBlockName( clientId, [
		'wbs/tabs',
	] );

	useEffect( () => {
		const newBlockIndex = editor.getBlockIndex( clientId );
		const blockIndexChange = newBlockIndex !== blockIndex;
		if ( blockIndexChange ) {
			setAttributes( { blockIndex: newBlockIndex } );
			dispatch( 'core/block-editor' ).updateBlockAttributes(
				parentBlockID,
				{
					updateChild: true,
				}
			);
		}
	}, [ blockIndex, clientId, editor, parentBlockID, setAttributes ] );

	const onChangeTabLabel = ( newTabLabel ) => {
		const getBlockIndex = editor.getBlockIndex( clientId );
		setAttributes( { tabLabel: newTabLabel } );
		setAttributes( { blockIndex: getBlockIndex } );
		dispatch( 'core/block-editor' ).updateBlockAttributes( parentBlockID, {
			updateChild: true,
		} );
	};

	return (
		<div { ...useBlockProps() }>
			<h4>Tab Label</h4>
			<TextControl
				className={ 'tab-label_input' }
				placeholder="Add Tab Label"
				type="text"
				value={ tabLabel }
				onChange={ onChangeTabLabel }
				__nextHasNoMarginBottom
			/>
			<h4>Tab Content</h4>
			<InnerBlocks />
		</div>
	);
}
