import { getContext, getElement, store } from '@wordpress/interactivity';

store( 'siteHeaderInViewObserver', {
	callbacks: {
		init: () => {
			const { IntersectionObserver } = window;
			const context = getContext();
			const el = getElement();
			const observer = new IntersectionObserver( ( [ entry ] ) => {
				toggleRootMenuClass( entry?.isIntersecting );
			}, context?.options || {} );

			observer?.observe( el.ref );
		},
	},
} );

function toggleRootMenuClass( isActive ) {
	document.documentElement.classList.toggle(
		'has-site-header-in-view',
		isActive
	);
}
