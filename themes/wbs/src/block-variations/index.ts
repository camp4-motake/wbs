import { variations } from './blockVariations';
import './index.css';

export const blockVariations = () => {
	if ( ! window?.wp?.blocks || ! variations.length ) {
		return;
	}

	variations.forEach(
		( s: { block?: string; variations?: unknown } ) =>
			window.wp?.blocks?.registerBlockVariation( s.block, s.variations )
	);
};

blockVariations();
