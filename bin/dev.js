/* eslint-disable no-console */
const { spawn } = require( 'node:child_process' );
const fs = require( 'node:fs' );
const path = require( 'node:path' );
const { workspaces } = require( '../package.json' );

function validateWorkspace( ws ) {
	try {
		const packageJsonPath = path.join( process.cwd(), ws, 'package.json' );

		if ( ! fs.existsSync( packageJsonPath ) ) {
			console.warn(
				`[\x1b[33mINFO\x1b[0m] Skipping ${ ws }: package.json not found`
			);
			return false;
		}

		const packageJson = require( packageJsonPath );

		if ( ! packageJson.scripts?.dev ) {
			console.warn(
				`[\x1b[33mINFO\x1b[0m] Skipping ${ ws }: dev script not found in package.json`
			);
			return false;
		}

		return true;
	} catch ( error ) {
		console.error(
			`[\x1b[31mERROR\x1b[0m] Error validating ${ ws }:`,
			error.message
		);
		return false;
	}
}

const tasks = workspaces?.filter( validateWorkspace )?.map( ( ws ) => ( {
	workspace: ws,
	command: 'npm',
	args: [ 'run', 'dev', `--workspace=${ ws }` ],
} ) );

function spawnProcess( { workspace, command, args }, index ) {
	const processEnv = { ...process.env };

	const childProcess = spawn( command, args, {
		stdio: [ 'inherit', 'pipe', 'pipe' ],
		shell: true,
		env: {
			...processEnv,
			FORCE_COLOR: 'true',
			npm_config_color: 'always',
		},
	} );

	const str = workspace?.replace( 'source/wp-content/', '' ) || index;
	const prefix = `[\x1b[36m${ str }\x1b[0m]`;

	childProcess.stdout.on( 'data', ( data ) => {
		data.toString()
			.split( '\n' )
			.filter( ( line ) => line.trim() )
			.forEach( ( line ) => {
				console.log( `${ prefix } ${ line }` );
			} );
	} );

	childProcess.stderr.on( 'data', ( data ) => {
		data.toString()
			.split( '\n' )
			.filter( ( line ) => line.trim() )
			.forEach( ( line ) => {
				console.error( `${ prefix } \x1b[31m${ line }\x1b[0m` );
			} );
	} );

	childProcess.on( 'close', ( code ) => {
		if ( code !== 0 ) {
			console.error(
				`${ prefix } \x1b[31mProcess exited with code ${ code }\x1b[0m`
			);
		}
	} );

	return childProcess;
}

function runParallel() {
	if ( ! tasks?.length ) {
		console.error(
			'\x1b[31mNo valid workspaces found with dev scripts\x1b[0m'
		);
		return;
	}

	console.log(
		'\x1b[32mStarting dev servers for valid workspaces...\x1b[0m'
	);
	const processes = tasks.map( ( task, index ) =>
		spawnProcess( task, index )
	);

	process.on( 'SIGINT', () => {
		console.log( '\n\x1b[33mGracefully shutting down...\x1b[0m' );
		processes.forEach( ( proc ) => proc.kill() );
		process.exit( 0 );
	} );
}

runParallel();
