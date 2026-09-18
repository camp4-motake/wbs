import { __ } from '@wordpress/i18n';
import type { SelectOption } from '../../shared/types';

export const MIN_POSTS = 1;
export const MAX_POSTS = 30;

export const POST_TYPES: SelectOption[] = [
	{ label: __( 'ニュース', 'wbs' ), value: 'news' },
];

export const TAXONOMIES: Record< string, SelectOption[] > = {
	news: [
		{ label: __( 'ニュースカテゴリー', 'wbs' ), value: 'category-news' },
	],
};
