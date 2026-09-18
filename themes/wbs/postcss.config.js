const postcssEasyImport = require( 'postcss-easy-import' );
const postcssPresetEnv = require( 'postcss-preset-env' );
const postcssUrl = require( 'postcss-url' );
const cssnano = require( 'cssnano' );

module.exports = ( { mode } ) => {
	const plugins = [
		postcssEasyImport(),
		// `image-set-function` is disabled so raw `url()`s survive into the built
		// CSS, where bin/build-images.mjs rewrites them into `image-set()` with
		// AVIF variants — keep the two in sync.
		postcssPresetEnv( {
			stage: 2,
			features: { 'image-set-function': false },
		} ),
		postcssUrl( { filter: '**/_inline/*', url: 'inline' } ),
	];

	if ( mode === 'production' ) {
		plugins.push( cssnano( { preset: 'default' } ) );
	}

	return { plugins };
};
