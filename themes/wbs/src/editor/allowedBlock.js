/**
 * 利用可能ブロックを制限
 */

const ALLOWED_BLOCK_LISTS = [
	'core/paragraph',
	'core/image',
	'core/heading',
	// "core/gallery",
	'core/list',
	'core/list-item',
	'core/quote',
	// "core/archives",
	'core/audio',
	'core/button',
	'core/buttons',
	// "core/calendar",
	// "core/categories",
	// 'core/freeform',
	'core/code',
	'core/column',
	'core/columns',
	'core/cover',
	'core/details',
	'core/embed',
	'core/file',
	'core/group',
	'core/html',
	// "core/latest-comments",
	// "core/latest-posts",
	'core/media-text',
	'core/missing',
	// 'core/more',
	// "core/nextpage",
	// 'core/page-list',
	// 'core/page-list-item',
	'core/pattern',
	'core/preformatted',
	'core/pullquote',
	'core/block',
	// "core/rss",
	// "core/search",
	'core/separator',
	'core/shortcode',
	// 'core/social-link',
	// 'core/social-links',
	'core/spacer',
	'core/table',
	// "core/tag-cloud",
	'core/text-columns',
	// "core/verse",
	'core/video',
	// "core/footnotes",
	'core/navigation',
	'core/navigation-link',
	'core/navigation-submenu',
	'core/site-logo',
	'core/site-title',
	'core/site-tagline',
	// 'core/query',
	'core/template-part',
	// "core/avatar",
	'core/post-title',
	// 'core/post-excerpt',
	'core/post-featured-image',
	'core/post-content',
	// "core/post-author",
	// "core/post-author-name",
	'core/post-date',
	// 'core/post-terms',
	// 'core/post-navigation-link',
	'core/post-template',
	// 'core/query-pagination',
	// 'core/query-pagination-next',
	// 'core/query-pagination-numbers',
	// 'core/query-pagination-previous',
	// 'core/query-no-results',
	// "core/read-more",
	// "core/comments",
	// "core/comment-author-name",
	// "core/comment-content",
	// "core/comment-date",
	// "core/comment-edit-link",
	// "core/comment-reply-link",
	// "core/comment-template",
	// "core/comments-title",
	// "core/comments-pagination",
	// "core/comments-pagination-next",
	// "core/comments-pagination-numbers",
	// "core/comments-pagination-previous",
	// "core/post-comments-form",
	'core/home-link',
	// "core/loginout",
	// "core/term-description",
	// 'core/query-title',
	// "core/post-author-biography",
	// "core/legacy-widget",
	// "core/widget-group",
	// "safe-svg/svg-icon",
	// "yoast-seo/breadcrumbs",
	// "yoast/how-to-block",
	// "yoast/faq-block",
];

// embedの許可ブロック以外を解除
const ALLOWED_EMBED_VARIATION = [
	'twitter',
	'youtube',
	'spotify',
	'vimeo',
	'bluesky',
];

export const allowedBlock = () => {
	if ( ! wp.blocks ) {
		return;
	}

	// 許可フィルター
	wp.blocks.getBlockTypes()?.forEach( ( block ) => {
		if (
			block.name.includes( 'wbs/' ) ||
			block.name.includes( 'flexible-table-block/' ) ||
			// block.name.includes( 'safe-svg/' ) ||
			// block.name.includes( 'acf/' ) ||
			ALLOWED_BLOCK_LISTS.includes( block.name )
		) {
			return;
		}
		wp.blocks.unregisterBlockType( block.name );
	} );

	wp.blocks.getBlockVariations( 'core/embed' )?.forEach( ( v ) => {
		if ( ALLOWED_EMBED_VARIATION.includes( v.name ) ) {
			return;
		}
		wp.blocks.unregisterBlockVariation( 'core/embed', v.name );
	} );
};
