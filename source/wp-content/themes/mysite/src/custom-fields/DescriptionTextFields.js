/**
 * カスタムフィールド: 投稿概要文 入力パネル追加
 */

import { TextareaControl } from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import { __ } from '@wordpress/i18n';
import { registerPlugin } from '@wordpress/plugins';

const ALLOWED_POST_TYPES = [ 'materials' ];

const DescriptionTextFields = () => {
	const { postID, postType } = useSelect( ( select ) => {
		const coreEditor = select( 'core/editor' );
		return {
			postID: coreEditor.getCurrentPostId(),
			postType: coreEditor.getCurrentPostType(),
		};
	}, [] );

	const [ meta, setMeta ] = useEntityProp(
		'postType',
		postType,
		'meta',
		postID
	);

	if ( ! postType?.includes( ALLOWED_POST_TYPES ) ) {
		return null;
	}

	const updateMetaValue = ( value ) => {
		setMeta( { ...meta, _meta_post_description: value } );
	};

	return (
		<PluginDocumentSettingPanel
			name="custom-toggle-panel-description"
			title={ __( '記事設定', 'wbs' ) }
			className="custom-toggle-panel"
		>
			<TextareaControl
				label={ __( '記事の概要文', 'wbs' ) }
				help={ __( '記事の一覧などに表示される概要文', 'wbs' ) }
				value={ meta?._meta_post_description }
				onChange={ updateMetaValue }
				__nextHasNoMarginBottom
			/>
		</PluginDocumentSettingPanel>
	);
};

registerPlugin( 'wbs-sidebar-meta-fields-post-description', {
	render: DescriptionTextFields,
	icon: 'admin-settings',
} );
