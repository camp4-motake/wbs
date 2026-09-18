import { isMatchURL } from './conditional';

const EXCLUDE_HASH_VALUES = [ '', '#', '#0', '#todo' ];

export function initAnchorScroll() {
	document
		.querySelectorAll< HTMLAnchorElement >( 'a[href*="#"]' )
		.forEach( ( el ) => {
			if ( isMatchURL( el.href ) ) {
				el.addEventListener( 'click', handleAnchorClick );
			}
		} );

	window.addEventListener( 'popstate', ( event ) => {
		const state = event.state as { hash?: string } | null;
		if ( ! state?.hash ) {
			return;
		}
		const targetElement = document.querySelector( state.hash );
		if ( targetElement ) {
			scrollToElement( targetElement );
		}
	} );
}

function handleAnchorClick( event: MouseEvent ) {
	const anchor = ( event.target as HTMLElement | null )?.closest( 'a' );
	if ( ! anchor?.href ) {
		return;
	}

	const url = new URL( anchor.href );
	if ( ! url.hash || EXCLUDE_HASH_VALUES.includes( url.hash ) ) {
		return;
	}

	const targetElement = document.querySelector( url.hash );
	if ( ! targetElement ) {
		return;
	}

	event.preventDefault();

	// 履歴にエントリを追加
	window.history.pushState( { hash: url.hash }, '', url.hash );

	scrollToElement( targetElement );
}

function scrollToElement( element: Element ) {
	element.scrollIntoView( {
		behavior: 'smooth',
		block: 'start',
		inline: 'nearest',
	} );
}
