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

	cfg.module.rules.push( {
		test: /\.(jpe?g|png|gif|svg)$/i,
		type: 'asset',
	} );
}

module.exports = config;
