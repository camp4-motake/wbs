export function generateUUID() {
	if ( typeof crypto !== 'undefined' && crypto.randomUUID ) {
		return crypto.randomUUID();
	}

	// fallback
	// RFC 4122 version 4 UUID (xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx)
	return 'xxxxxxxx-xxxx-4xxx-yxxx-xxxxxxxxxxxx'.replace( /[xy]/g, ( c ) => {
		const r = Math.floor( Math.random() * 16 );
		const v = c === 'x' ? r : ( r % 4 ) + 8;
		return v.toString( 16 );
	} );
}
