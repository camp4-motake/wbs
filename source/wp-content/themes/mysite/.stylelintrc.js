const { propertyGroups } = require( 'stylelint-config-clean-order' );

module.exports = {
	extends: [
		'@wordpress/stylelint-config',
		'stylelint-config-clean-order',
		'stylelint-config-prettier', // WORKAROUND: Stylelint v15 or later is deprecated.
	],
	rules: {
		'no-descending-specificity': null,

		'comment-empty-line-before': [
			'always',
			{ except: [ 'first-nested' ] },
		],

		'rule-empty-line-before': [
			'always',
			{ ignore: [ 'after-comment', 'first-nested' ] },
		],

		'at-rule-empty-line-before': [
			'always',
			{
				ignore: [
					'after-comment',
					'first-nested',
					'blockless-after-same-name-blockless',
					'blockless-after-blockless',
				],
			},
		],

		// @see https://github.com/humanmade/coding-standards/issues/193#issuecomment-1405099508
		'selector-class-pattern': [
			'^[a-z]([-]?[a-z0-9]+)*(__[a-z0-9]([-]?[a-z0-9]+)*)?(--[a-z0-9]([-]?[a-z0-9]+)*)?$',
			{
				resolveNestedSelectors: true,
				message: ( selectorValue ) => {
					return `Expected class selector "${ selectorValue }" to match lowercase and separate words with hyphens or BEM CSS pattern https://en.bem.info/methodology/css. Selector validation tool: https://regexr.com/3apms`;
				},
			},
		],

		// @see https://github.com/kutsan/stylelint-config-clean-order
		'order/order': null,
		'order/properties-order': [
			propertyGroups.map( ( properties ) => ( {
				noEmptyLineBetween: true,
				emptyLineBefore: 'never', // Don't add empty lines between order groups.
				properties,
			} ) ),
			{
				severity: 'warning',
				unspecified: 'bottomAlphabetical',
			},
		],
	},
	ignoreFiles: [
		'build/**/*.css',
		'node_modules/**/*.css',
		'vendor/**/*.css',
		'**/*.min.css',
		'**/*.js',
		'**/*.ts',
		'**/*.tsx',
	],
};
