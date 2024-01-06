module.exports = {
	extends: ['@wordpress/stylelint-config'],
	ignoreFiles: [
		'build/**/*.css',
		'node_modules/**/*.css',
		'vendor/**/*.css',
		'**/*.js',
		'**/*.ts',
		'**/*.tsx',
	],
	rules: {
		// "no-descending-specificity": null,
		// "font-weight-notation": null,
		// "selector-class-pattern": null,
		// "value-keyword-case": [
		// 	"lower",
		// 	{
		// 		camelCaseSvgKeywords: true,
		// 	},
		// ],
	},
};
