// shared config
module.exports = ( ctx ) => ( {
	plugins: {
		'postcss-easy-import': {},
		'postcss-preset-env': { stage: 2, features: { 'nesting-rules': true } },
		'postcss-url': { filter: '**/_inline/*', url: 'inline' },
		...( ctx.mode === 'production' && { cssnano: { preset: 'default' } } ),
	},
} );
