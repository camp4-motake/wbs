import {
	registerExtendBlockStyles,
	type ExtendBlockStylesConfig,
} from './registerExtendBlockStyles';
// import { __ } from '@wordpress/i18n';

/**
 * ブロックスタイル拡張
 * ブロックスタイル以外の追加クラス付け替え用スイッチ項目を追加する
 */

const config: ExtendBlockStylesConfig[] = [
	/*
	// example
	{
		blockNames: [ 'core/buttons' ],
		panelTitle: __( `ボタンタイプ`, 'wbs' ),
		selectionType: 'select', // 'select' | 'radio'
		styleOptions: [
			{ label: '小', value: 'has-size-sm' },
			{ label: '大', value: 'has-size-lg' },
		],
	},
	*/
];

export const blockStylesExtension = (): void => {
	if ( ! config?.length ) {
		return;
	}
	for ( const cfg of config ) {
		registerExtendBlockStyles( cfg );
	}
};
