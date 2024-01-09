const { existsSync } = require('node:fs');
const { workspaces } = require('./package.json');
const wpEnv = existsSync('./.wp-env.override.json')
	? require('./.wp-env.override.json')
	: require('./.wp-env.json');

module.exports = {
	files: workspaces.flatMap((path) => [
		`${path}/**/*.{php,html,json}`,
		`${path}/build`,
		`!${path}/{vendor,node_modules}`,
		`!${path}/.*/*`,
	]),
	ghostMode: false,
	notify: false,
	open: false,
	proxy: process.env.PROXY_URL || `http://localhost:${wpEnv?.port || 8888}`,
	ui: false,
};
