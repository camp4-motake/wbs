/**
 * カスタムフィールド: リンク 入力パネル追加
 */

import {
    // WORKAROUND
    // eslint-disable-next-line @wordpress/no-unsafe-wp-apis
    __experimentalLinkControl as LinkControl,
} from '@wordpress/block-editor';
import { Button, PanelRow, Popover } from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { Icon, external, link } from '@wordpress/icons';
import { registerPlugin } from '@wordpress/plugins';

const ALLOWED_POST_TYPES = [ 'materials' ];

const CustomLinkFields = () => {
	const [ isEditingLink, setIsEditingLink ] = useState( false );
	const { postType } = useSelect( ( select ) => {
		const coreEditor = select( 'core/editor' );
		return {
			postType: coreEditor.getCurrentPostType(),
		};
	}, [] );

	const [ meta, setMeta ] = useEntityProp( 'postType', postType, 'meta' );

	if ( ! postType?.includes( ALLOWED_POST_TYPES ) ) {
		return null;
	}

	const linkSettings = meta?._meta_link_settings || {
		url: '',
		title: '',
		target: '_self',
		rel: [],
	};

	// rel属性の値を設定から解析する関数
	const getRelValuesFromSettings = ( settings ) => {
		const relValues = [];
		if ( settings.noFollow ) {
			relValues.push( 'nofollow' );
		}
		if ( settings.noReferrer ) {
			relValues.push( 'noreferrer' );
		}
		if ( settings.sponsored ) {
			relValues.push( 'sponsored' );
		}
		if ( settings.ugc ) {
			relValues.push( 'ugc' );
		}
		return relValues;
	};

	// 設定をrel属性の値から解析する関数
	const getSettingsFromRel = ( rel ) => {
		return {
			noFollow: rel.includes( 'nofollow' ),
			noReferrer: rel.includes( 'noreferrer' ),
			sponsored: rel.includes( 'sponsored' ),
			ugc: rel.includes( 'ugc' ),
		};
	};

	const updateLinkSettings = ( newValue ) => {
		if ( ! newValue ) {
			setMeta( {
				...meta,
				_meta_link_settings: {
					url: '',
					title: '',
					target: '_self',
					rel: [],
				},
			} );
			setIsEditingLink( false );
			return;
		}

		// rel属性の値を設定から取得
		const relValues = getRelValuesFromSettings( newValue );

		setMeta( {
			...meta,
			_meta_link_settings: {
				url: newValue.url || '',
				title: newValue.title || '',
				target: newValue.opensInNewTab ? '_blank' : '_self',
				rel: relValues,
			},
		} );
		setIsEditingLink( false );
	};

	const currentValue = linkSettings.url
		? {
				url: linkSettings.url,
				title: linkSettings.title,
				opensInNewTab: linkSettings.target === '_blank',
				...getSettingsFromRel( linkSettings.rel ),
		  }
		: null;

	const LinkInfo = () => {
		if ( ! linkSettings.url ) {
			return (
				<span style={ { color: '#757575' } }>
					{ __( 'リンクが設定されていません', 'wbs' ) }
				</span>
			);
		}

		return (
			<div>
				<div style={ { fontWeight: '500', marginBottom: '4px' } }>
					{ linkSettings.title || linkSettings.url }
				</div>
				<div style={ { fontSize: '13px', color: '#757575' } }>
					{ linkSettings.url }
					{ linkSettings.target === '_blank' && (
						<span
							style={ {
								marginLeft: '8px',
								verticalAlign: 'middle',
							} }
						>
							<Icon
								icon={ external }
								style={ {
									width: '16px',
									height: '16px',
									display: 'inline-block',
								} }
							/>
						</span>
					) }
					{ linkSettings.rel.length > 0 && (
						<span style={ { marginLeft: '8px' } }>
							({ linkSettings.rel.join( ', ' ) })
						</span>
					) }
				</div>
			</div>
		);
	};

	return (
		<PluginDocumentSettingPanel
			name="custom-toggle-panel-link-field"
			title={ __( 'リンク設定', 'wbs' ) }
			initialOpen={ true }
		>
			<PanelRow>
				<div style={ { width: '100%' } }>
					<div
						style={ {
							display: 'grid',
							gap: '0.5rem',
							marginBottom: '8px',
						} }
					>
						<div>
							<LinkInfo />
						</div>
						<div>
							<Button
								icon={ link }
								onClick={ () => setIsEditingLink( true ) }
								variant="secondary"
							>
								{ __( 'リンク編集', 'wbs' ) }
							</Button>
						</div>
					</div>

					{ isEditingLink && (
						<Popover
							position="top right"
							onClose={ () => setIsEditingLink( false ) }
						>
							<div style={ { padding: '12px' } }>
								<LinkControl
									value={ currentValue }
									onChange={ updateLinkSettings }
									hasRichPreviews
									createSuggestionButtonText={ __(
										'新規リンクを作成',
										'wbs'
									) }
									hasTextControl={ true }
									showInitialSuggestions={ true }
									settings={ [
										{
											id: 'opensInNewTab',
											title: __(
												'新しいタブで開く',
												'wbs'
											),
										},
										{
											id: 'noFollow',
											title: __(
												'検索エンジンにフォローさせない',
												'wbs'
											),
										},
										{
											id: 'sponsored',
											title: __(
												'スポンサー付きコンテンツとして指定',
												'wbs'
											),
										},
										{
											id: 'ugc',
											title: __(
												'ユーザー生成コンテンツとして指定',
												'wbs'
											),
										},
									] }
									suggestionsQuery={ {
										type: 'post',
										subtype: 'any',
									} }
									onRemove={ () => {
										updateLinkSettings( null );
									} }
								/>
							</div>
						</Popover>
					) }
				</div>
			</PanelRow>
		</PluginDocumentSettingPanel>
	);
};

registerPlugin( 'wbs-sidebar-meta-fields-custom-link', {
	render: CustomLinkFields,
	icon: 'admin-settings',
} );
