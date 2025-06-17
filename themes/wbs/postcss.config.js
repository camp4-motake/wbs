const postcssEasyImport = require( 'postcss-easy-import' );
const postcssPresetEnv = require( 'postcss-preset-env' );
const postcssUrl = require( 'postcss-url' );
const cssnano = require( 'cssnano' );

module.exports = ( { mode } ) => {
	const plugins = [
		postcssEasyImport(),
		postcssPresetEnv( { stage: 2, features: {} } ),
		postcssUrl( { filter: '**/_inline/*', url: 'inline' } ),
	];

	if ( mode === 'production' ) {
		plugins.push( cssnano( { preset: 'default' } ) );
	}

	return { plugins };
};
