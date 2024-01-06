const CopyPlugin = require('copy-webpack-plugin');
const ImageMinimizerPlugin = require('image-minimizer-webpack-plugin');
const defaultConfig = require('@wordpress/scripts/config/webpack.config');

const svgFilter = (_source, sourcePath) => !/\.(svg)$/i.test(sourcePath);
const config = defaultConfig;

config.stats = 'minimal';

config.optimization.minimizer.push(
	new ImageMinimizerPlugin({
		minimizer: [
			{
				implementation: ImageMinimizerPlugin.sharpMinify,
				filter: svgFilter,
				options: { encodeOptions: {} },
			},
			{
				implementation: ImageMinimizerPlugin.svgoMinify,
				options: {
					encodeOptions: {
						multipass: true,
						plugins: ['preset-default'],
					},
				},
			},
		],
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
	new CopyPlugin({ patterns: [{ from: 'src/images', to: 'images' }] })
);

module.exports = config;
