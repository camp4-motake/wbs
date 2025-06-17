import {
	BlockControls,
	InnerBlocks,
	InspectorControls,
	LinkControl,
	useBlockProps,
} from '@wordpress/block-editor';
import {
	PanelBody,
	Popover,
	ToolbarButton,
	ToolbarGroup,
} from '@wordpress/components';
import { useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import { link as linkIcon, linkOff } from '@wordpress/icons';
import './editor.css';

const ALLOWED_BLOCKS = [
	'core/heading',
	'core/paragraph',
	'core/buttons',
	'core/image',
];

export default function Edit( { attributes, setAttributes } ) {
	const { link } = attributes;
	const [ isLinkPopoverOpen, setIsLinkPopoverOpen ] = useState( false );

	const blockProps = useBlockProps( {
		className: 'swiper-slide',
		style: link?.url ? { cursor: 'pointer' } : {}, // リンクが設定されている場合のスタイル調整
	} );

	const linkValue = link?.url
		? {
				url: link.url,
				title: link.title,
				opensInNewTab: link.target === '_blank',
		  }
		: {};

	const onLinkChange = ( newLink ) => {
		setAttributes( {
			link: newLink?.url
				? {
						url: newLink.url || '',
						title: newLink.title || '',
						target: newLink.opensInNewTab ? '_blank' : '_self',
						rel: newLink.opensInNewTab ? 'noopener noreferrer' : '',
				  }
				: null,
		} );
	};

	const toggleLinkPopover = () => {
		setIsLinkPopoverOpen( ! isLinkPopoverOpen );
	};

	const unlinkButton = () => {
		setAttributes( { link: null } );
		setIsLinkPopoverOpen( false );
	};

	return (
		<>
			<BlockControls>
				<ToolbarGroup>
					<ToolbarButton
						icon={ linkIcon }
						title={ __( 'リンク', 'wbs' ) }
						onClick={ toggleLinkPopover }
						isActive={ link?.url }
						className={ link?.url ? 'is-pressed' : '' }
					/>
					{ link?.url && (
						<ToolbarButton
							icon={ linkOff }
							title={ __( 'リンクを削除', 'wbs' ) }
							onClick={ unlinkButton }
						/>
					) }
				</ToolbarGroup>

				{ isLinkPopoverOpen && (
					<Popover
						placement="bottom"
						onClose={ () => setIsLinkPopoverOpen( false ) }
						anchorRef={ null }
						focusOnMount="firstElement"
					>
						<div style={ { padding: '16px', minWidth: '280px' } }>
							<LinkControl
								value={ linkValue }
								onChange={ ( newLink ) => {
									onLinkChange( newLink );
								} }
								settings={ [
									{
										id: 'opensInNewTab',
										title: __( '新しいタブで開く', 'wbs' ),
									},
								] }
								showSuggestions={ true }
								withCreateSuggestion={ true }
								createSuggestionButtonText={ ( searchTerm ) => {
									return (
										__( '新規作成:', 'wbs' ) + searchTerm
									);
								} }
							/>
						</div>
					</Popover>
				) }
			</BlockControls>

			<InspectorControls>
				<PanelBody
					title={ __( 'リンク設定', 'wbs' ) }
					initialOpen={ true }
				>
					{ link?.url && (
						<div
							style={ {
								marginTop: '16px',
								fontSize: '12px',
								color: '#666',
							} }
						>
							<strong>
								{ __( '設定されたリンク :', 'wbs' ) }
							</strong>
							<br />
							URL: { link.url }
							<br />
							{ link.title && (
								<>
									タイトル: { link.title }
									<br />
								</>
							) }
							ターゲット: { link.target }
							<br />
							{ link.rel && <>Rel: { link.rel }</> }
						</div>
					) }
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				{ link?.url && (
					<div
						style={ {
							position: 'absolute',
							top: '8px',
							right: '8px',
							background: '#0073aa',
							color: 'white',
							padding: '4px 8px',
							borderRadius: '3px',
							fontSize: '11px',
							zIndex: 1,
						} }
					>
						🔗 リンク設定あり
					</div>
				) }
				<InnerBlocks
					allowedBlocks={ ALLOWED_BLOCKS }
					template={ [
						[
							'core/image',
							{ className: 'has-force-thumbnail-display' },
						],
						[ 'core/paragraph', {} ],
					] }
				/>
			</div>
		</>
	);
}
