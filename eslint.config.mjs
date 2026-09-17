/**
 * External dependencies
 */
import globals from 'globals';

/**
 * WordPress dependencies
 */
import wordpress from '@wordpress/eslint-plugin';

const unusedVarsOptions = {
	args: 'after-used',
	argsIgnorePattern: '^_',
	ignoreRestSiblings: true,
};

export default [
	{
		// Global ignores (migrated from .eslintignore).
		ignores: [
			'**/node_modules/**',
			'**/vendor/**',
			'**/build/**',
			'**/.claude/**',
			'plugins/*',
			'themes/*',
			'!themes/wbs',
			'**/static/**',
		],
	},
	...wordpress.configs.recommended,
	{
		// Migrated from `env: { browser: true }`.
		languageOptions: {
			globals: {
				...globals.browser,
			},
		},
	},
	{
		// JS files: report unused vars as warnings with the same options as TS files.
		files: [ '**/*.js', '**/*.jsx', '**/*.mjs', '**/*.cjs' ],
		rules: {
			'no-unused-vars': [ 'warn', unusedVarsOptions ],
		},
	},
	{
		// TS files: relax @typescript-eslint/no-unused-vars from the WordPress
		// recommended preset (error) to a warning with ignore patterns.
		files: [ '**/*.ts', '**/*.tsx' ],
		rules: {
			'@typescript-eslint/no-unused-vars': [ 'warn', unusedVarsOptions ],
		},
	},
];
