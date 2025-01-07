/**
 * ブロックスタイル登録
 * @see https://ja.wordpress.org/team/handbook/block-editor/reference-guides/block-api/block-styles/
 */

// 追加ブロックスタイル
const ADD = [
	/* // example
	{
		block: 'core/button',
		option: { name: 'icon-none', label: 'アイコンなし' },
	},
	{
		block: 'core/button',
		option: { name: 'arrow-left', label: '左矢印' },
	},
	 */
];

// 削除するデフォルトスタイル
const REMOVED = [
	{ block: 'core/button', style: 'outline' },
	{ block: 'core/button', style: 'fill' },
	// { block: 'core/image', style: 'rounded' },
];

export const blockStyles = () => {
	if ( ! window?.wp?.blocks ) {
		return;
	}

	ADD?.forEach( ( s ) =>
		wp?.blocks?.registerBlockStyle( s.block, s.option )
	);
	REMOVED.forEach( ( s ) =>
		wp.blocks.unregisterBlockStyle( s.block, s.style )
	);
};
