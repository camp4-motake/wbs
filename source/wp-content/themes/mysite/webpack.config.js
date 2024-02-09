const CopyPlugin = require('copy-webpack-plugin');
const ImageMinimizerPlugin = require('image-minimizer-webpack-plugin');
const config = require('@wordpress/scripts/config/webpack.config');

const svgFilter = (_source, sourcePath) => !/\.(svg)$/i.test(sourcePath);

config.stats = 'minimal';

config.plugins.push(
	new ImageMinimizerPlugin({
		test: /\.(svg)$/i,
		minimizer: {
			implementation: ImageMinimizerPlugin.svgoMinify,
			options: {
				encodeOptions: {
					multipass: true,
					plugins: ['preset-default'],
				},
			},
		},
	}),
	new ImageMinimizerPlugin({
		test: /\.(png|jpe?g|gif)$/i,
		generator: [
			{
				type: 'asset',
				implementation: ImageMinimizerPlugin.sharpGenerate,
				filter: svgFilter,
				options: { encodeOptions: { webp: { quality: 90 } } },
			},
		],
	})
);

config.plugins.push(
	new CopyPlugin({
		patterns: [
			{
				from: 'src/images',
				to: 'images',
				globOptions: { ignore: ['**/_inline/**'] },
				noErrorOnMissing: true,
			},
		],
	})
);

module.exports = config;
