/**
 * ブロックバリエーション
 * @see https://ja.wordpress.org/team/handbook/block-editor/reference-guides/block-api/block-variations/
 */

export const blockVariations = () => {
	if ( ! window?.wp?.blocks ) {
		return;
	}

	const addVariations = [
		/*
		// ex
    { block: "core/embed", variations: [ { name: "custom-embed", attributes: { providerNameSlug: "custom" }, }, ], },
    */
	];

	if ( ! addVariations.length ) {
		return;
	}

	addVariations.forEach( ( s ) =>
		wp?.blocks?.registerBlockVariation( s.block, s.variations )
	);
};
