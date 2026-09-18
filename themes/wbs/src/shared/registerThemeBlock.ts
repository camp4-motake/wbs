import { registerBlockType } from '@wordpress/blocks';
import type { BlockSaveProps } from '@wordpress/blocks';
import type { ComponentType } from 'react';
import type { BlockEditPropsWithName } from './types';

type BlockSettings< A extends Record< string, unknown > > = Parameters<
	typeof registerBlockType< A >
>[ 1 ];

/**
 * registerBlockType の文字列名オーバーロードは attributes/category/title を
 * 必須にするが、当テーマでは block.json(PHP 側登録)が供給するため部分指定を
 * 許可する。型上のギャップはこのラッパー内の 1 箇所だけでアサートする。
 */
export type ThemeBlockSettings< A extends Record< string, unknown > > = Record<
	string,
	unknown
> & {
	title?: string;
	description?: string;
	edit?: ComponentType< BlockEditPropsWithName< A > >;
	save?: ComponentType< BlockSaveProps< A > >;
};

export function registerThemeBlock<
	A extends Record< string, unknown > = Record< string, unknown >,
>( name: string, settings: ThemeBlockSettings< A > ) {
	return registerBlockType( name, settings as unknown as BlockSettings< A > );
}
