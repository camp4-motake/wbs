import { debounce } from 'es-toolkit';

export const wpAdminBarSize = () => {
	const wpAdminBar = document.getElementById( 'wpadminbar' );
	setWPAdminBarSizeCSSProperty( wpAdminBar );

	if ( ! wpAdminBar ) {
		return;
	}

	const { ResizeObserver } = window;
	const resizeObserver = new ResizeObserver(
		debounce( ( entries ) => {
			if ( entries[ 0 ].contentBoxSize ) {
				setWPAdminBarSizeCSSProperty( wpAdminBar );
			}
		}, 50 )
	);
	resizeObserver.observe( document.body );
};

/**
 * set css property
 * @param {Element} wpAdminBar
 */
function setWPAdminBarSizeCSSProperty( wpAdminBar ) {
	const size = wpAdminBar?.getBoundingClientRect();
	document.documentElement.style.setProperty(
		'--wp-adminbar-block-size',
		`${ size?.height || 0 }px`
	);
	document.documentElement.style.setProperty(
		'--wp-adminbar-inline-size',
		`${ size?.width || 0 }px`
	);
}
