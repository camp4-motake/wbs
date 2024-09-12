module.exports = {
	extends: [ 'plugin:@wordpress/eslint-plugin/recommended' ],
	rules: { 'no-unused-vars': 'warn' },
	overrides: [
		{
			files: [ '*.ts', '*.tsx' ],
			extends: [ 'plugin:@typescript-eslint/recommended' ],
			plugins: [ '@typescript-eslint' ],
			parser: '@typescript-eslint/parser',
			parserOptions: {
				sourceType: 'module',
			},
			rules: {},
		},
	],
};
