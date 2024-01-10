module.exports = {
	extends: [
		'@wordpress/stylelint-config',
		'stylelint-config-recess-order',
		'stylelint-config-prettier', // WORKAROUND: Stylelint v15 deprecated.
	],
	ignoreFiles: [
		'build/**/*.css',
		'node_modules/**/*.css',
		'vendor/**/*.css',
		'**/*.js',
		'**/*.ts',
		'**/*.tsx',
	],
	rules: {
		'comment-empty-line-before': null,
		'no-descending-specificity': null,
		'selector-class-pattern': null,
		'rule-empty-line-before': null,
	},
};
