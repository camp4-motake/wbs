/**
 * カスタムフィールド: サイドバーに「Pickup記事」入力パネル追加
 */

import { CheckboxControl } from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import {
	PluginDocumentSettingPanel,
	store as editorStore,
} from '@wordpress/editor';
import { __ } from '@wordpress/i18n';
import { usePostMeta, type PostMeta } from '../../shared/hooks/usePostMeta';

type PickupPostMeta = PostMeta & {
	wbs_is_pickup_post?: boolean;
};

export const PickupPostFields = () => {
	const { postID, postType } = useSelect( ( select ) => {
		return {
			postID: select( editorStore ).getCurrentPostId(),
			postType: select( editorStore ).getCurrentPostType(),
		};
	}, [] );

	const [ meta, setMeta ] = usePostMeta< PickupPostMeta >( postType, postID );

	const updateToggleValue = ( value: boolean ) => {
		setMeta( { ...meta, wbs_is_pickup_post: value } );
	};

	return (
		<PluginDocumentSettingPanel
			name="custom-toggle-panel"
			title={ __( '記事設定', 'wbs' ) }
			className="custom-toggle-panel"
		>
			<CheckboxControl
				label={ __( 'Pickup 記事', 'wbs' ) }
				checked={ meta?.wbs_is_pickup_post }
				onChange={ updateToggleValue }
			/>
		</PluginDocumentSettingPanel>
	);
};

/* example
registerPlugin( 'wbs-theme-sidebar-meta-fields-pickup-post', {
	render: PickupPostFields,
	icon: 'admin-settings',
} );
*/
