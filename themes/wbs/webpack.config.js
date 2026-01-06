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

	if ( process.env.NODE_ENV === 'production' ) {
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
			} ),

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

			/*
			// other options
			new ImageMinimizerPlugin( {
				minimizer: {
					implementation: ImageMinimizerPlugin.sharpMinify,
					options: {
						encodeOptions: {
							jpeg: { quality: 80 },
							webp: { quality: 90, smartSubsample: true },
							avif: { quality: 85 },
							png: { palette: false, quality: 80, }, // [!] Impact on AVIF image quality
						},
					},
				},
			})
			*/
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
