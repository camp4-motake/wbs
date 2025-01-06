import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { serialize } from '@wordpress/blocks';
import { useSelect } from '@wordpress/data';
import { useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';
import './editor.css';

const ALLOWED_BLOCKS = [ 'wbs/contents-carousel-slide' ];

export default function Edit( { clientId, name, attributes, setAttributes } ) {
	const blockProps = useBlockProps();
	const [ isEditing, setIsEditing ] = useState( false );
	const renderKey = `${ name }-${ attributes.contentUpdated || Date.now() }`;

	// InnerBlocksの内容を監視 - select関数を最適化
	const innerBlocksContent = useSelect(
		( select ) => {
			const { getBlock } = select( 'core/block-editor' );
			const block = getBlock( clientId );
			if ( ! block ) {
				return '';
			}

			// innerBlocksが空の場合は空文字列を返す
			if ( ! block.innerBlocks || block.innerBlocks.length === 0 ) {
				return '';
			}

			return serialize( block.innerBlocks );
		},
		[ clientId ]
	);

	// InnerBlocksの内容が変更されたら属性を更新
	useEffect( () => {
		// 前回の値と同じ場合は更新しない
		if ( innerBlocksContent === attributes.innerContent ) {
			return;
		}

		// 非同期でステート更新
		const timeoutId = setTimeout( () => {
			setAttributes( {
				innerContent: innerBlocksContent,
				contentUpdated: Date.now(),
			} );
		}, 0 );

		return () => clearTimeout( timeoutId );
	}, [ attributes.innerContent, innerBlocksContent, setAttributes ] );

	return (
		<>
			<div { ...blockProps }>
				{ isEditing ? (
					<div className="edit-mode">
						<div className="toggle-edit-button-wrapper">
							<button
								onClick={ () => setIsEditing( false ) }
								className="toggle-edit-button components-button is-primary"
							>
								{ __( '戻る', 'wbs' ) }
							</button>
						</div>
						<div className="edit-mode__inner">
							<div className="edit-slider">
								<h3 className="edit-slider-heading">
									{ __( 'スライド編集', 'wbs' ) }
								</h3>
								<div className="edit-slide">
									<InnerBlocks
										allowedBlocks={ ALLOWED_BLOCKS }
										renderAppender={
											InnerBlocks.ButtonBlockAppender
										}
									/>
								</div>
							</div>
						</div>
					</div>
				) : (
					<div className="preview-mode">
						<div className="toggle-edit-button-wrapper">
							<button
								onClick={ () => setIsEditing( true ) }
								className="toggle-edit-button components-button is-primary"
							>
								{ __( 'スライド編集', 'wbs' ) }
							</button>
						</div>
						<ServerSideRender
							key={ renderKey }
							block={ name }
							attributes={ attributes }
							httpMethod="POST"
						/>
					</div>
				) }
			</div>
		</>
	);
}
