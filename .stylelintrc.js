module.exports = {
	extends: [
		'@wordpress/stylelint-config',
		'stylelint-config-recess-order', // WORKAROUND: limit v3.x (v4.x not resolved)
		'stylelint-config-prettier', // WORKAROUND: Stylelint v15 or later is deprecated.
	],
	rules: {
		'comment-empty-line-before': ['always', { except: ['first-nested'] }],
		'no-descending-specificity': null,
		'selector-class-pattern': null,
		'rule-empty-line-before': null,
	},
	ignoreFiles: [
		'build/**/*.css',
		'node_modules/**/*.css',
		'vendor/**/*.css',
		'**/*.js',
		'**/*.ts',
		'**/*.tsx',
	],
};
