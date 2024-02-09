const { existsSync } = require('node:fs');
const { workspaces } = require('./package.json');
const { PROXY_URL, WP_ENV_PORT } = process.env;
const wpEnv = {
	...require('./.wp-env.json'),
	...(existsSync('./.wp-env.override.json')
		? require('./.wp-env.override.json')
		: {}),
};
const wpPort = WP_ENV_PORT || wpEnv?.port || 8888;

module.exports = {
	files: workspaces?.flatMap((path) => [
		`${path}/**/*.{php,html,json}`,
		`${path}/build`,
		`!${path}/{vendor,node_modules}`,
		`!${path}/.*/*`,
	]),
	ghostMode: false,
	notify: false,
	open: false,
	proxy: PROXY_URL || `http://localhost:${wpPort}`,
	ui: false,
};
