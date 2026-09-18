import type { BlockEditProps } from '@wordpress/blocks';

/**
 * SelectControl / RadioControl などに渡す選択肢の共通形
 */
export type SelectOption = {
	label: string;
	value: string;
	disabled?: boolean;
};

/**
 * LinkControl とやり取りするリンク値の共通形（コアブロック同様のオブジェクト）。
 * 未設定は空オブジェクト `{}` で表す。
 */
export type LinkValue = {
	url?: string;
	opensInNewTab?: boolean;
};

/**
 * バンドル版 @wordpress/blocks の BlockEditProps はランタイムで渡される
 * name を含まないため、ここで一度だけ補完する
 */
export type BlockEditPropsWithName<
	A extends Record< string, unknown > = Record< string, unknown >,
> = BlockEditProps< A > & { name: string };
