import { getContext, getElement, store } from '@wordpress/interactivity';

const { state } = store( 'contentsCarousel', {
	state: {
		isCarouselActive: false,
		api: null,
		el: null,
	},

	callbacks: {
		*initCarouse() {
			const { Swiper } = yield import(
				/* webpackPreload: true */
				'swiper'
			);
			const { A11y, Navigation, Pagination } = yield import(
				/* webpackPreload: true */
				// eslint-disable-next-line import/no-unresolved
				'swiper/modules'
			);
			const context = getContext();
			const { ref } = getElement();
			const el = ( selector ) => ref?.querySelector( selector ) || null;

			state.el = ref.querySelector( '.swiper' );

			state.api = new Swiper( state.el, {
				modules: [ A11y, Navigation, Pagination ],
				slidesPerView: 'auto',
				rewind: true,
				speed: Number( context?.option?.speed || 600 ),
				a11y: { enabled: true },
				autoHeight: true,
				navigation: {
					nextEl: el( '.slider-btn-next' ),
					prevEl: el( '.slider-btn-prev' ),
				},
				pagination: {
					el: el( '.swiper-pagination' ),
					clickable: true,
				},
				on: {
					afterInit: () => {
						state.isCarouselActive = true;
					},
				},
			} );
		},
	},
} );
