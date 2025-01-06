export const inView = ( options = {} ) => {
	const target = document.querySelectorAll( '[data-scroll]' );
	const { IntersectionObserver } = window;
	const observer = [];

	target.forEach( ( el, i ) => {
		const isRepeat = el.hasAttribute( 'data-scroll-repeat' );

		observer.push(
			new IntersectionObserver( ( [ entry ] ) => {
				// repeat
				if ( isRepeat ) {
					const attr = entry.isIntersecting ? 'in' : 'out';
					el?.setAttribute( 'data-scroll', attr );
					return;
				}

				// once
				if ( entry.isIntersecting ) {
					el?.setAttribute( 'data-scroll', 'in' );
					observer[ i ].unobserve( el );
				}
			}, options )
		);
		observer[ i ].observe( el );
	} );
};
