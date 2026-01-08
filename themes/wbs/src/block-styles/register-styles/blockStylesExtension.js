import { registerExtendBlockStyles } from './registerExtendBlockStyles';
// import { __ } from '@wordpress/i18n';

/**
 * ブロックスタイル拡張
 * ブロックスタイル以外の追加クラス付け替え用スイッチ項目を追加する
 */

const config = [
	/*
	// example
	{
		blockNames: [ 'core/buttons' ],
		panelTitle: __( `ボタンタイプ`, 'hmj' ),
		selectionType: 'select', // 'select' | 'radio'
		styleOptions: [
			{ label: '小', value: 'has-size-sm' },
			{ label: '大', value: 'has-size-lg' },
		],
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
