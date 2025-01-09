module.exports = {
	extends: [ 'plugin:@wordpress/eslint-plugin/recommended' ],
	rules: { 'no-unused-vars': 'warn' },
	settings: { react: { version: '18.3' } },
};
