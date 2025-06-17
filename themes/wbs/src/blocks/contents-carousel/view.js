import { getContext, getElement, store } from '@wordpress/interactivity';

store( 'contentsCarousel', {
	callbacks: {
		*initCarousel() {
			const { Swiper } = yield import(
				/* webpackPreload: true */
				'swiper'
			);
			const { A11y, EffectCreative, Navigation, Pagination } =
				yield import(
					/* webpackPreload: true */
					// eslint-disable-next-line import/no-unresolved
					'swiper/modules'
				);
			const context = getContext();
			const { ref } = getElement();
			const el = ( selector ) => ref?.querySelector( selector ) || null;

			context.el = ref.querySelector( '.swiper' );
			context.api = new Swiper( context.el, {
				modules: [ A11y, EffectCreative, Navigation, Pagination ],
				slidesPerView: 'auto',
				speed: 400,
				rewind: true,
				a11y: { enabled: true },
				autoHeight: false,
				grabCursor: true,
				spaceBetween: '2.5%',
				navigation: {
					nextEl: el( '.swiper-button-next' ),
					prevEl: el( '.swiper-button-prev' ),
				},
				pagination: {
					el: el( '.swiper-pagination' ),
					clickable: true,
				},
				on: {
					init() {
						updateSlideNumbers( this );
					},
					slideChange() {
						updateSlideNumbers( this );
					},
					afterInit: () => {
						context.isCarouselActive = true;
					},
					// resize: debounce( () => { context.api.update(); }, 400 ),
				},
				...( context?.option?.speed && {
					speed: Number( context?.option?.speed ),
				} ),
			} );
		},
	},
} );

function updateSlideNumbers( swiper ) {
	swiper.slides.forEach( ( slide ) => {
		const ariaLabel = slide.getAttribute( 'aria-label' );
		const slideNumber = ariaLabel.split( ' / ' )[ 0 ];
		slide.setAttribute( 'data-slide-number', slideNumber );
	} );
}
