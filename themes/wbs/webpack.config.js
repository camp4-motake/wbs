const CopyPlugin = require( 'copy-webpack-plugin' );
const ImageMinimizerPlugin = require( 'image-minimizer-webpack-plugin' );
const config = require( '@wordpress/scripts/config/webpack.config' );
const imgFilter = ( _source, sourcePath ) => ! /\.(svg)$/i.test( sourcePath );

/**
 * webpack config
 * @see https://webpack.js.org/configuration/
 */
for ( const cfg of config ) {
	cfg.stats = 'minimal';

	cfg.plugins.push(
		new CopyPlugin( {
			patterns: [
				{
					from: 'src/images',
					to: 'images',
					globOptions: { ignore: [ '**/_*/**' ] },
					noErrorOnMissing: true,
				},
			],
		} )
	);

	cfg.plugins.push(
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
		} )
	);

	if ( process.env.NODE_ENV === 'production' ) {
		cfg.plugins.push(
			new ImageMinimizerPlugin( {
				deleteOriginalAssets: false,
				generator: [
					...[ { avif: { quality: 85 } } ].map( ( opt ) => ( {
						type: 'asset',
						implementation: ImageMinimizerPlugin.sharpGenerate,
						filter: imgFilter,
						options: { encodeOptions: opt },
					} ) ),
				],
			} )
		);
	}

	cfg.module.rules.push( {
		test: /\.(jpe?g|png|gif|svg)$/i,
		type: 'asset',
	} );

	cfg.performance = {
		maxAssetSize: 512000,
		assetFilter( assetFilename ) {
			return ! /\.(jpe?g|png|webp|avif)$/i.test( assetFilename );
		},
	};
}

module.exports = config;
