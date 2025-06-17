export const isTouchDevice = () => {
	return 'ontouchstart' in document && 'orientation' in window;
};

export const isWPAdminBar = () => !! document.getElementById( 'wpadminbar' );

export const isMatchURL = ( href ) => {
	const loc = new URL( window.location.href );
	const tgt = new URL( href );

	return (
		`${ loc.origin }${ loc.pathname }${ loc.search }` ===
		`${ tgt.origin }${ tgt.pathname }${ tgt.search }`
	);
};

export const isExternalUrl = ( url ) => {
	try {
		const urlObj = new URL( url, window.location.href );
		return urlObj.hostname !== window.location.hostname;
	} catch ( error ) {
		return false;
	}
};

export const isObject = ( value ) => {
	return value !== null && typeof value === 'object';
};
