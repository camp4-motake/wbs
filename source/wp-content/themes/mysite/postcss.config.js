module.exports = ( ctx ) => ( {
	plugins: {
		'@csstools/postcss-global-data': {
			files: [ './src/main/config/custom-media.css' ],
		},
		'postcss-easy-import': {},
		'postcss-preset-env': {
			stage: 2,
			features: { 'nesting-rules': true },
		},
		'postcss-url': { filter: '**/_inline/*', url: 'inline' },
		...( ctx.mode === 'production'
			? { cssnano: { preset: 'default' } }
			: {} ),
	},
} );
