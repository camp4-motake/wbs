import type { BlockVariation } from '@wordpress/blocks';

/**
 * 1 つの対象ブロックと、それに登録するバリエーション（単体 or 複数）。
 * バリエーションは `<name>/index.ts` でこの型に従って定義し、index.ts で集約する。
 */
export type BlockVariationSetting = {
	block: string;
	variations: BlockVariation | BlockVariation[];
};
