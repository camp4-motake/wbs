module.exports = (ctx) => {
	const inProduction = ctx.mode === 'production';

	return {
		plugins: {
			'postcss-preset-env': {
				stage: 2,
				features: { 'nesting-rules': true },
			},
			'postcss-sort-media-queries': {},
			...(inProduction ? { cssnano: { preset: 'default' } } : null),
		},
	};
};
