import { registerExtendBlockStyles } from './registerExtendBlockStyles';

/**
 * ブロックスタイル拡張
 * ブロックスタイル以外の追加クラス付け替え用スイッチ項目を追加する
 */

const config = [
	/* // example
	{
		blockNames: [ 'core/buttons' ],
		panelTitle: `ボタンタイプ`,
		styleOptions: [
			{ label: '小', value: 'has-size-sm' },
			{ label: '大', value: 'has-size-lg' },
		],
		selectionType: 'select', // 'select' | 'radio'
	},
	 */
];

export const blockStylesExtension = () => {
	if ( ! config?.length ) {
		return;
	}
	for ( const cfg of config ) {
		registerExtendBlockStyles( cfg );
	}
};
