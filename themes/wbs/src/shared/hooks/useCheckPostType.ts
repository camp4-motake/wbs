import { useSelect } from '@wordpress/data';

// @wordpress/editor の store ディスクリプタを import すると消費側エントリーの
// asset.php に wp-editor が追加されてしまうため、文字列キー + ローカル
// interface で型を付ける
interface CoreEditorSelectors {
	getCurrentPostType: () => string | undefined;
}

export const useCheckPostType = ( postTypes: string[] = [] ) => {
	const { postType } = useSelect( ( select ) => {
		const coreEditor = select( 'core/editor' ) as CoreEditorSelectors;
		return { postType: coreEditor.getCurrentPostType() };
	}, [] );

	return [ postType !== undefined && postTypes.includes( postType ) ];
};
