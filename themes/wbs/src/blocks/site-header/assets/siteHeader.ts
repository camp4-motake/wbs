import { store } from '@wordpress/interactivity';

const { classList } = document.documentElement;

const { state, actions } = store( 'siteHeader', {
	state: {
		menu: { shown: false },
		nav: { outerEl: null, innerEl: null },
	},
	actions: {
		menu: {
			toggle: () => {
				state.menu.shown = ! state.menu.shown;
				toggleRootMenuClass( state.menu.shown );
			},
			close: () => {
				state.menu.shown = false;
				toggleRootMenuClass( false );
			},
		},
	},
	callbacks: {
		init: () => {
			toggleRootMenuClass( false );
			setOuterClickEvent();
		},
		menu: {
			title: () => ( state.menu.shown ? 'Menu Close' : 'Menu Open' ),
		},
	},
} );

function toggleRootMenuClass( shown = false ) {
	classList.toggle( 'has-menu-shown', shown );
}

function setOuterClickEvent() {
	const ignoreCloseSelector = [ '[data-menu-close-ignore]' ].join( ',' );

	document.addEventListener( 'click', ( { target } ) => {
		if ( ! ( target as HTMLElement )?.closest( ignoreCloseSelector ) ) {
			actions.menu.close();
		}
	} );
}
