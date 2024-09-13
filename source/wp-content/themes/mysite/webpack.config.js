const CopyPlugin = require( 'copy-webpack-plugin' );
const ImageMinimizerPlugin = require( 'image-minimizer-webpack-plugin' );
const config = require( '@wordpress/scripts/config/webpack.config' );
const imgFilter = ( _source, sourcePath ) => ! /\.(svg)$/i.test( sourcePath );

/**
 * Stats
 * @see https://webpack.js.org/configuration/stats/
 */
config.stats = 'minimal';

/**
 * Cache
 * @see https://webpack.js.org/configuration/cache/
 */
config.cache = {
	type: 'filesystem',
	buildDependencies: { config: [ __filename ] },
};

/**
 * Image minimize
 * @see https://github.com/webpack-contrib/image-minimizer-webpack-plugin
 */
config.plugins.push(
	new ImageMinimizerPlugin( {
		test: /\.(svg)$/i,
		minimizer: {
			implementation: ImageMinimizerPlugin.svgoMinify,
			options: {
				encodeOptions: {
					multipass: true,
					plugins: [ 'preset-default' ],
				},
			},
		},
	} ),

	new ImageMinimizerPlugin( {
		minimizer: {
			implementation: ImageMinimizerPlugin.sharpMinify,
			options: {
				encodeOptions: {
					jpeg: { quality: 80 },
					webp: { quality: 90, smartSubsample: true },
					avif: { lossless: true },
					png: { quality: 80 },
				},
			},
		},
		generator: [
			{
				type: 'asset',
				implementation: ImageMinimizerPlugin.sharpGenerate,
				filter: imgFilter,
				options: {
					encodeOptions: {
						webp: { quality: 90, smartSubsample: true },
					},
				},
			},
		],
	} ),

	/**
	 * Copy Files
	 */
	new CopyPlugin( {
		patterns: [
			{
				from: 'src/images',
				to: 'images',
				globOptions: { ignore: [ '**/_inline/**' ] },
				noErrorOnMissing: true,
			},
		],
	} )
);

config.module.rules.push( {
	test: /\.(jpe?g|png|gif|svg)$/i,
	type: 'asset',
} );

/**
 * Performance
 * @see https://webpack.js.org/configuration/performance/#performance-hints
 */
config.performance = {
	maxAssetSize: 768000,
	maxEntrypointSize: 512000,
	assetFilter( assetFilename ) {
		return assetFilename.endsWith( '.js' );
	},
};

module.exports = config;
