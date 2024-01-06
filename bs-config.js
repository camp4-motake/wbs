const { existsSync } = require('node:fs');

const wpEnvOr = existsSync('./.wp-env.override.json')
	? require('./.wp-env.override.json')
	: {};

const config = {
	ui: false,
	files: [
		'source/wp-content/plugins/mysite/**/*.{php,html}',
		'source/wp-content/plugins/mysite/build',
		'source/wp-content/themes/mysite/**/*.{php,html}',
		'source/wp-content/themes/mysite/build',
	],
	proxy: process.env.PROXY_URL || `http://localhost:${wpEnvOr?.port || 8888}`,
	ghostMode: false,
	open: false,
	notify: false,
};

module.exports = config;
