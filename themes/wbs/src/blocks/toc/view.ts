/**
 * 目次（front / Interactivity API）
 *
 * render.php は本文の見出しに id が無い場合、`heading-<level>-<n>` という合成アンカーを
 * 生成して目次リンクの href に使う（n は本文中の全 h1–h6 を出現順に数えた 1 始まりの
 * 通し番号）。ただし本文側の見出しには id を付与しないため、そのままでは目次リンクの
 * ジャンプ先が存在しない。
 *
 * このストアは `data-wp-init` で目次ブロックごとに 1 度だけ走り、本文（the_content の
 * 出力）を走査して、render.php と同じ通し番号で id を持たない対象見出しにアンカー id を
 * 付与する。付与は目次が実際に参照している合成アンカーに
 * 一致した場合のみ行うため、走査範囲が想定とずれても誤った id を振ることはない。
 *
 * 注: id を書き込む本文見出しは interactive 領域の外なので、ハイドレーションとは干渉しない。
 */

import { getElement, store } from '@wordpress/interactivity';

const CONTENT_SELECTOR = '.entry-content, .wp-block-post-content';
const HEADING_SELECTOR = 'h1, h2, h3, h4, h5, h6';

function assignAnchors( toc: HTMLElement ): void {
	const content = toc.closest< HTMLElement >( CONTENT_SELECTOR );
	if ( ! content ) {
		return;
	}

	// 目次が参照している合成アンカー（# 始まり）を集める。
	const referenced = new Set< string >();
	toc.querySelectorAll< HTMLAnchorElement >(
		'.toc__link[href^="#"]'
	).forEach( ( link ) => {
		const id = decodeURIComponent( link.hash.slice( 1 ) );
		if ( id ) {
			referenced.add( id );
		}
	} );
	if ( 0 === referenced.size ) {
		return;
	}

	// render.php と同じ通し番号（本文中の全 h1–h6 を出現順に数える）で id を再構成し、
	// id を持たない見出しのうち目次が参照しているものにだけ付与する。
	content
		.querySelectorAll< HTMLElement >( HEADING_SELECTOR )
		.forEach( ( heading, index ) => {
			if ( heading.id ) {
				return;
			}
			const level = Number( heading.tagName.charAt( 1 ) );
			const anchor = `heading-${ level }-${ index + 1 }`;
			if ( referenced.has( anchor ) ) {
				heading.id = anchor;
			}
		} );
}

store( 'wbs/toc', {
	callbacks: {
		init: () => {
			const { ref } = getElement();
			if ( ref ) {
				assignAnchors( ref );
			}
		},
	},
} );
