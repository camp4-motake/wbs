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
		'at-rule-empty-line-before': null,
		'rule-empty-line-before': null,
	},
};
