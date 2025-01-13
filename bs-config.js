const { existsSync } = require( 'node:fs' );
const { relative } = require( 'node:path' );
const { findWorkspaces } = require( 'find-workspaces' );

const { workspaces } = findWorkspaces()?.map( ( ws ) =>
	relative( process.cwd(), ws?.location )
);
const wpEnv = [ './.wp-env.json', './.wp-env.override.json' ].reduce(
	( acc, env ) => ( existsSync( env ) ? { ...acc, ...require( env ) } : acc ),
	{}
);
const { PROXY_URL, WP_ENV_PORT } = process.env;
const wpPort = WP_ENV_PORT || wpEnv?.port || 8888;

module.exports = {
	files: workspaces?.flatMap( ( path ) => [
		`${ path }/**/*.{php,html,json}`,
		`${ path }/build`,
		`!${ path }/{vendor,node_modules}`,
		`!${ path }/.*/*`,
	] ),
	ghostMode: false,
	notify: false,
	open: false,
	proxy: PROXY_URL || `http://localhost:${ wpPort }`,
	ui: false,
};
