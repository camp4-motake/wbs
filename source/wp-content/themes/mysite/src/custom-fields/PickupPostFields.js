/**
 * カスタムフィールド: Pickup記事 入力パネル追加
 */

import { CheckboxControl } from '@wordpress/components';
import { useEntityProp } from '@wordpress/core-data';
import { useSelect } from '@wordpress/data';
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import { __ } from '@wordpress/i18n';
import { registerPlugin } from '@wordpress/plugins';

const ALLOWED_POST_TYPES = [ 'news' ];

const PickupPostFields = () => {
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

	const updateToggleValue = ( value ) => {
		setMeta( { ...meta, _meta_is_pickup_post: value } );
	};

	return (
		<PluginDocumentSettingPanel
			name="custom-toggle-panel"
			title={ __( '記事設定', 'wbs' ) }
			className="custom-toggle-panel"
		>
			<CheckboxControl
				label={ __( 'Pickup 記事', 'wbs' ) }
				checked={ meta?._meta_is_pickup_post }
				onChange={ updateToggleValue }
				__nextHasNoMarginBottom
			/>
		</PluginDocumentSettingPanel>
	);
};

registerPlugin( 'wbs-sidebar-meta-fields-pickup-post', {
	render: PickupPostFields,
	icon: 'admin-settings',
} );
