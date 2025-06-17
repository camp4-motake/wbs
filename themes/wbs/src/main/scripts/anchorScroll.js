import { isMatchURL } from './conditional';

const EXCLUDE_HASH_VALUES = [ '', '#', '#0', '#todo' ];

export function initAnchorScroll() {
	document.querySelectorAll( 'a[href*="#"]' ).forEach( ( el ) => {
		if ( isMatchURL( el.href ) ) {
			el.addEventListener( 'click', handleAnchorClick );
		}
	} );

	window.addEventListener( 'popstate', ( event ) => {
		if ( ! event.state?.hash ) {
			return;
		}
		const targetElement = document.querySelector( event.state.hash );
		if ( targetElement ) {
			scrollToElement( targetElement );
		}
	} );
}

function handleAnchorClick( event ) {
	const anchor = event?.target?.closest( 'a' );
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

function scrollToElement( element ) {
	element.scrollIntoView( {
		behavior: 'smooth',
		block: 'start',
		inline: 'nearest',
	} );
}
