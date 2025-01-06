import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { serialize } from '@wordpress/blocks';
import { useSelect } from '@wordpress/data';
import { useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';
import './editor.css';

const ALLOWED_BLOCKS = [
	'core/button',
	'core/buttons',
	'core/column',
	'core/columns',
	'core/group',
	'core/heading',
	'core/image',
	'core/list',
	'core/list-item',
	'core/navigation',
	'core/navigation-link',
	'core/navigation-submenu',
	'core/paragraph',
	'core/separator',
];

export default function Edit( { clientId, name, attributes, setAttributes } ) {
	const [ isEditing, setIsEditing ] = useState( false );
	const blockProps = useBlockProps( { className: 'footer' } );

	// InnerBlocksの内容を監視
	const innerBlocksContent = useSelect(
		( select ) => {
			const { getBlock } = select( 'core/block-editor' );
			const block = getBlock( clientId );
			return block ? serialize( block.innerBlocks ) : '';
		},
		[ clientId ]
	);

	// InnerBlocksの内容が変更されたら属性を更新
	useEffect( () => {
		if ( innerBlocksContent !== attributes.innerContent ) {
			setAttributes( {
				innerContent: innerBlocksContent,
				contentUpdated: Date.now(),
			} );
		}
	}, [ attributes.innerContent, innerBlocksContent, setAttributes ] );

	return (
		<div { ...blockProps }>
			{ isEditing ? (
				<div className="edit-mode">
					<div className="toggle-edit-button-wrapper">
						<button
							onClick={ () => setIsEditing( false ) }
							className="toggle-edit-button components-button is-primary"
						>
							{ __( 'プレビュー', 'wbs' ) }
						</button>
					</div>
					<div className="edit-mode__inner">
						<InnerBlocks
							allowedBlocks={ ALLOWED_BLOCKS }
							templateLock={ false }
						/>
					</div>
				</div>
			) : (
				<div className="preview-mode">
					<div className="toggle-edit-button-wrapper">
						<button
							onClick={ () => setIsEditing( true ) }
							className="toggle-edit-button components-button is-primary"
						>
							{ __( '編集', 'wbs' ) }
						</button>
					</div>
					<ServerSideRender
						block={ name }
						attributes={ {
							...attributes,
							innerContent: innerBlocksContent,
						} }
						httpMethod="POST"
					/>
				</div>
			) }
		</div>
	);
}
