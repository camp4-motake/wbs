import { registerFormatType } from '@wordpress/rich-text';
import type { RichTextValue } from '@wordpress/rich-text';

export type FormatSettings = Parameters< typeof registerFormatType >[ 1 ];

/**
 * フォーマットの edit コンポーネントが受け取る props(必要なものだけ定義)
 */
export interface FormatEditProps {
	value: RichTextValue;
	onChange: ( value: RichTextValue ) => void;
	onFocus: () => void;
	isActive: boolean;
	isObjectActive: boolean;
}

/**
 * 当テーマのフォーマット設定は WPFormat の必須プロパティ
 * (interactive/object)を省略したり独自キーを持つため、ランタイム
 * オブジェクトを変えずに型上のギャップだけここでアサートする。
 *
 * @param name     フォーマット名
 * @param settings フォーマット設定
 */
export const registerThemeFormat = (
	name: string,
	settings: Partial< FormatSettings > & Record< string, unknown >
): ReturnType< typeof registerFormatType > =>
	registerFormatType( name, settings as FormatSettings );
