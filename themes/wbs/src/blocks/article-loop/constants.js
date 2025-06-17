import { __ } from '@wordpress/i18n';

export const MIN_POSTS = 1;
export const MAX_POSTS = 30;

export const POST_TYPES = [ { label: __( 'ニュース', 'wbs' ), value: 'news' } ];

export const TAXONOMIES = {
	news: [
		{ label: __( 'ニュースカテゴリー', 'wbs' ), value: 'category-news' },
	],
};
