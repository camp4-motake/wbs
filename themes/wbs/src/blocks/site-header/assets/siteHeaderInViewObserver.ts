import { getContext, getElement, store } from '@wordpress/interactivity';

interface SiteHeaderContext {
	options: {};
}

store( 'siteHeaderInViewObserver', {
	callbacks: {
		init: () => {
			const { IntersectionObserver } = window;
			const context = getContext< SiteHeaderContext >();
			const { ref } = getElement();
			const observer = new IntersectionObserver(
				( [ entry ] ) => {
					toggleRootMenuClass( entry?.isIntersecting );
				},
				context?.options || {}
			);

			if ( ref ) {
				observer?.observe( ref );
			}
		},
	},
} );

function toggleRootMenuClass( isActive ) {
	document.documentElement.classList.toggle(
		'has-site-header-in-view',
		isActive
	);
}
