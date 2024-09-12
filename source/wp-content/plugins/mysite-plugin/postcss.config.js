module.exports = ( ctx ) => ( {
	plugins: {
		'postcss-preset-env': {
			stage: 2,
			features: { 'nesting-rules': true },
		},
		'postcss-sort-media-queries': {},
		...( ctx.mode === 'production'
			? { cssnano: { preset: 'default' } }
			: {} ),
	},
} );
