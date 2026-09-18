import { MediaUpload, MediaUploadCheck } from '@wordpress/block-editor';
import { Button, PanelRow } from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

export const CustomImagePanel = ( {
	metaSlug = '',
	panelLabel = '',
	panelDescription = '',
} ) => {
	const [ isLoading, setIsLoading ] = useState( false );

	// 現在の投稿タイプ
	const { postID, postType } = useSelect( ( select ) => {
		const coreEditor = select( 'core/editor' );
		return {
			postID: coreEditor.getCurrentPostId(),
			postType: coreEditor.getCurrentPostType(),
		};
	}, [] );

	// useEntityPropを使用してメタデータを管理
	const [ meta, setMeta ] = useEntityProp(
		'postType',
		postType,
		'meta',
		postID
	);

	// 現在の画像IDを取得（useEntityPropから直接取得）
	const imageId = meta[ metaSlug ] || 0;

	// 画像情報を取得
	const imageData = useSelect(
		( select ) => {
			if ( ! imageId ) {
				return null;
			}
			return select( 'core' ).getMedia( imageId );
		},
		[ imageId ]
	);

	// 画像を設定
	const setImage = async ( media ) => {
		setIsLoading( true );
		await setMeta( { ...meta, [ metaSlug ]: media.id } );
		setTimeout( () => setIsLoading( false ), 500 );
	};

	// 画像を削除
	const removeImage = async () => {
		setIsLoading( true );
		await setMeta( { ...meta, [ metaSlug ]: 0 } );
		setTimeout( () => setIsLoading( false ), 300 );
	};

	// MARK: アップロードボタン
	const renderUploadButton = ( { open } ) => {
		let label = __( '処理中…' );

		if ( ! isLoading ) {
			label = imageId ? __( '画像を変更' ) : __( '画像をアップロード' );
		}

		return (
			<Button
				onClick={ open }
				variant="secondary"
				disabled={ isLoading }
				style={ { width: '100%', marginBottom: '12px' } }
				icon="format-image"
			>
				{ label }
			</Button>
		);
	};

	// MARK: 画像プレビューコンポーネント
	const ImagePreview = () => {
		if ( ! imageId || ! imageData ) {
			return null;
		}

		return (
			<div style={ { marginTop: '12px' } }>
				<div
					style={ {
						border: '1px solid #e0e0e0',
						padding: '12px',
						borderRadius: '6px',
						backgroundColor: '#fafafa',
						boxShadow: '0 1px 3px rgba(0,0,0,0.1)',
					} }
				>
					<img
						src={
							imageData.media_details?.sizes?.medium
								?.source_url || imageData.source_url
						}
						alt={ imageData.alt_text || '' }
						style={ {
							display: 'block',
							width: '100%',
							height: '100%',
							aspectRatio: '1',
							objectFit: 'cover',
							borderRadius: '4px',
							marginBottom: '12px',
						} }
						loading="lazy"
					/>

					<div
						style={ {
							fontSize: '13px',
							color: '#555',
							marginBottom: '12px',
							lineHeight: '1.4',
						} }
					>
						<div
							style={ { fontWeight: '600', marginBottom: '4px' } }
						>
							{ imageData.title?.rendered || 'Untitled' }
						</div>
						{ imageData.media_details?.width &&
							imageData.media_details?.height && (
								<div>
									寸法: { imageData.media_details.width } ×{ ' ' }
									{ imageData.media_details.height }px
								</div>
							) }

						{ imageData.media_details?.filesize && (
							<div>
								サイズ:{ ' ' }
								{ Math.round(
									imageData.media_details.filesize / 1024
								) }
								KB
							</div>
						) }

						{ imageData.mime_type && (
							<div>
								形式:{ ' ' }
								{ imageData.mime_type
									.split( '/' )[ 1 ]
									.toUpperCase() }
							</div>
						) }
					</div>

					<Button
						onClick={ removeImage }
						variant="secondary"
						isDestructive
						disabled={ isLoading }
						style={ { width: '100%' } }
					>
						{ isLoading ? __( '削除中…' ) : __( '画像を削除' ) }
					</Button>
				</div>
			</div>
		);
	};

	// MARK: 空状態コンポーネント
	const EmptyState = () => {
		if ( imageId ) {
			return null;
		}

		return (
			<div
				style={ {
					textAlign: 'center',
					padding: '24px 16px',
					border: '2px dashed #c3c4c7',
					borderRadius: '6px',
					color: '#757575',
					fontSize: '14px',
					marginTop: '12px',
					backgroundColor: '#fafafa',
				} }
			>
				<div style={ { marginBottom: '8px', fontSize: '16px' } }>
					📷
				</div>
				<div>{ __( '画像が設定されていません' ) }</div>
				<div
					style={ {
						fontSize: '12px',
						marginTop: '4px',
						opacity: 0.8,
					} }
				>
					{ __( '上のボタンから画像をアップロードしてください' ) }
				</div>
			</div>
		);
	};

	// MARK:
	return (
		<PluginDocumentSettingPanel
			name="custom-image-panel"
			title={ panelLabel }
			className="custom-image-panel"
		>
			<PanelRow>
				<div style={ { width: '100%' } }>
					{ panelDescription && (
						<div style={ { marginBlockEnd: '10px' } }>
							<small>{ panelDescription }</small>
						</div>
					) }
					<MediaUploadCheck>
						<MediaUpload
							onSelect={ setImage }
							allowedTypes={ [ 'image' ] }
							value={ imageId }
							render={ renderUploadButton }
						/>
					</MediaUploadCheck>
					<ImagePreview />
					<EmptyState />
				</div>
			</PanelRow>
		</PluginDocumentSettingPanel>
	);
};
