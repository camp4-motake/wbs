// import { __ } from '@wordpress/i18n';

interface AddedStyle {
	block: string;
	option: {
		name: string;
		label: string;
	};
}

interface RemovedStyle {
	block: string;
	style: string;
}

/**
 * 追加ブロックスタイル登録
 * @see https://ja.wordpress.org/team/handbook/block-editor/reference-guides/block-api/block-styles/
 */
const ADDED_STYLES: AddedStyle[] = [
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
const REMOVED_STYLES: RemovedStyle[] = [
	{ block: 'core/button', style: 'outline' },
	{ block: 'core/button', style: 'fill' },
];

export const blockStyles = (): void => {
	if ( ! window?.wp?.blocks ) {
		return;
	}

	ADDED_STYLES?.forEach(
		( s ) => window.wp?.blocks?.registerBlockStyle( s.block, s.option )
	);
};

export const removeBlockStyles = (): void => {
	if ( ! window?.wp?.blocks ) {
		return;
	}

	REMOVED_STYLES.forEach( ( s ) =>
		window.wp.blocks.unregisterBlockStyle( s.block, s.style )
	);
};
