import { getContext, getElement, store } from '@wordpress/interactivity';
import { initAccordions } from './accordion';

store( 'accordion', {
	callbacks: {
		init: () => {
			const { ref } = getElement();
			const context = getContext();
			initAccordions( ref, context?.isSingleOpen );
		},
	},
} );
