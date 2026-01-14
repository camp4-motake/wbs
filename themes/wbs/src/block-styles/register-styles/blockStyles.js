// import { __ } from '@wordpress/i18n';

/**
 * 追加ブロックスタイル登録
 * @see https://ja.wordpress.org/team/handbook/block-editor/reference-guides/block-api/block-styles/
 */
const ADDED_STYLES = [
	/*
	// example
	{
		block: 'core/buttons',
		option: {
			name: 'under-line',
			label: __( '下線リンク', 'wbs' ),
		},
	},
	*/
];

// 削除するデフォルトスタイル
const REMOVED_STYLES = [
	{ block: 'core/button', style: 'outline' },
	{ block: 'core/button', style: 'fill' },
];

export const blockStyles = () => {
	if ( ! window?.wp?.blocks ) {
		return;
	}

	ADDED_STYLES?.forEach( ( s ) =>
		wp?.blocks?.registerBlockStyle( s.block, s.option )
	);
};

export const removeBlockStyles = () => {
	if ( ! window?.wp?.blocks ) {
		return;
	}

	REMOVED_STYLES.forEach( ( s ) =>
		wp.blocks.unregisterBlockStyle( s.block, s.style )
	);
};
