import { isExternalUrl } from './conditional';

export const externalLinks = () => {
	const selector = 'a.wp-block-social-link-anchor';

	document
		.querySelectorAll< HTMLAnchorElement >( selector )
		.forEach( ( link ) => {
			if ( ! link?.href || ! isExternalUrl( link.href ) ) {
				return;
			}

			link.target = '_blank';
			link.rel = 'noreferrer';
		} );
};
