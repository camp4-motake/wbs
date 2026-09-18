import { useEntityProp } from '@wordpress/core-data';

export type PostMeta = Record< string, unknown >;

/**
 * 投稿メタの読み書きフック。useEntityProp の戻り値はアップストリームで
 * [any, Function, any] のままなので、ここで一度だけ型を付ける。
 *
 * @param postType 投稿タイプスラッグ
 * @param postId   投稿 ID(省略時は編集中の投稿)
 */
export const usePostMeta = < M extends PostMeta = PostMeta >(
	postType: string,
	postId?: number | string | null
) =>
	useEntityProp(
		'postType',
		postType,
		'meta',
		postId as number | string | undefined
	) as unknown as [ M, ( newMeta: M ) => Promise< void > ];
