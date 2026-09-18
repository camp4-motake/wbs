export const wpAdminBarSize = () => {
	const wpAdminBar = document.getElementById( 'wpadminbar' );
	setWPAdminBarSizeCSSProperty( wpAdminBar );

	if ( ! wpAdminBar ) {
		return;
	}

	const { ResizeObserver } = window;
	const resizeObserver = new ResizeObserver( ( entries ) => {
		if ( entries[ 0 ].contentBoxSize ) {
			setWPAdminBarSizeCSSProperty( wpAdminBar );
		}
	} );
	resizeObserver.observe( document.body );
};

/**
 * set css property
 * @param wpAdminBar
 */
function setWPAdminBarSizeCSSProperty( wpAdminBar: Element | null ) {
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
