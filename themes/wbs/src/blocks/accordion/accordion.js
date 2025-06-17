/**
 * Accordion function
 *
 * @param {Element} wrapper
 * @param {boolean} isSingleOpen
 */
export const initAccordions = ( wrapper, isSingleOpen = false ) => {
	const details = wrapper?.querySelectorAll( 'details' );
	const ctx = [];

	details?.forEach( ( el, i ) => {
		const summary = el?.querySelector( 'summary' );
		const panel = el?.querySelector( '[data-js-accordion-panel]' );

		if ( ! summary || ! panel ) {
			return;
		}

		const actions = {
			isExpand: ( value ) => {
				el.setAttribute( 'data-accordion-open', value );
			},
			isIconOpen: ( value ) => {
				el.classList.toggle( 'has-icon-open', value );
			},
			isOpen: ( value ) => {
				el.open = value;
			},
		};
		const isInitOpen = el.hasAttribute( 'open' );

		ctx[ i ] = new Proxy(
			{
				isExpand: false,
				isIconOpen: false,
				isOpen: false,
				isTransition: false,
			},
			{
				set( target, property, value ) {
					const oldValue = target[ property ];
					target[ property ] = value;
					if ( actions[ property ] && oldValue !== value ) {
						actions[ property ]( value );
					}
					return true;
				},
			}
		);

		if ( isInitOpen ) {
			open( ctx[ i ] );
		}

		summary.addEventListener( 'click', ( event ) => {
			if ( ctx[ i ].isTransition ) {
				return;
			}
			event.preventDefault();
			toggle( ctx[ i ] );
			ctx[ i ].isTransition = true;
			if ( isSingleOpen ) {
				setSingleOpen( ctx, i );
			}
		} );

		panel.addEventListener( 'transitionend', () => {
			ctx[ i ].isTransition = false;
			ctx[ i ].isOpen = ctx[ i ].isExpand;
		} );
	} );
};

const toggle = ( ctx ) => {
	if ( ctx.isExpand ) {
		close( ctx );
	} else {
		open( ctx );
	}
};

const close = ( ctx ) => {
	ctx.isIconOpen = false;
	ctx.isExpand = false;
};

const open = ( ctx ) => {
	ctx.isIconOpen = true;
	ctx.isOpen = true;
	window.requestAnimationFrame( () => {
		ctx.isExpand = true;
	} );
};

const setSingleOpen = ( ctx, index ) => {
	for ( const [ i, c ] of Object.entries( ctx ) ) {
		if ( Number( i ) === Number( index ) ) {
			continue;
		}
		close( c );
	}
};
