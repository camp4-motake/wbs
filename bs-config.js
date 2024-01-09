const { existsSync } = require('node:fs');

const packageJson = require('./package.json');
const wpEnv = require('./.wp-env.json');
const wpEnvOr = existsSync('./.wp-env.override.json')
	? require('./.wp-env.override.json')
	: {};
const port = wpEnvOr?.port || wpEnv?.port || 8888;

const config = {
	ui: false,
	files: packageJson.workspaces.flatMap((ws) => [
		`${ws}/**/*.{php,html,json}`,
		`${ws}/build`,
		`!${ws}/{vendor,node_modules}`,
	]),
	proxy: process.env.PROXY_URL || `http://localhost:${port}`,
	ghostMode: false,
	open: false,
	notify: false,
};

module.exports = config;
