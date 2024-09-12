/**
 * ブロックスタイル
 * @see https://ja.wordpress.org/team/handbook/block-editor/reference-guides/block-api/block-styles/
 */
export const blockStyles = () => {
	if ( ! wp?.blocks ) {
		return;
	}

	/**
	 * register styles
	 */
	const add = [
		{
			block: 'core/paragraph',
			option: { name: 'arrow-link', label: 'リンクに矢印を追加' },
		},
		{
			block: 'core/media-text',
			option: { name: 'delay', label: 'ディレイ' },
		},
		{
			block: 'core/table',
			option: { name: 'definition', label: '左セル強調' },
		},
		// { block: 'core/table', option: { name: 'bg-transparent', label: '背景色なし' }, },
		// { block: 'core/table', option: { name: 'all-border', label: '全枠' }, },
	];
	add.forEach( ( s ) => wp?.blocks?.registerBlockStyle( s.block, s.option ) );

	/**
	 * remove default core styles
	 */
	const remove = [
		{ block: 'core/button', style: 'outline' },
		{ block: 'core/button', style: 'squared' },
	];
	remove.forEach( ( s ) => wp.blocks.registerBlockStyle( s.block, s.style ) );
};
