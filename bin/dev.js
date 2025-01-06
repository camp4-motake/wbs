const { spawn } = require('node:child_process');
const fs = require('node:fs');
const path = require('node:path');
const { workspaces } = require('../package.json');

// ワークスペースの検証をシンプル化
const isValidWorkspace = (ws) => {
  const packagePath = path.join(process.cwd(), ws, 'package.json');
  try {
    const pkg = fs.existsSync(packagePath) && require(packagePath);
    return pkg && pkg.scripts?.dev;
  } catch {
    return false;
  }
};

// 子プロセスの作成と管理を単純化
const createProcess = (workspace) => {
  const prefix = `[${workspace.replace('source/wp-content/', '')}]`;
  const proc = spawn('npm', ['run', 'dev', `--workspace=${workspace}`], {
    stdio: ['inherit', 'pipe', 'pipe'],
    shell: true,
    env: { ...process.env, FORCE_COLOR: 'true', npm_config_color: 'always' }
  });

  const log = (data, isError = false) => {
    data.toString()
      .split('\n')
      .filter(line => line.trim())
      .forEach(line => {
        const message = isError ? `\x1b[31m${line}\x1b[0m` : line;
        console[isError ? 'error' : 'log'](`\x1b[36m${prefix}\x1b[0m ${message}`);
      });
  };

  proc.stdout.on('data', data => log(data));
  proc.stderr.on('data', data => log(data, true));
  proc.on('close', code => {
    if (code !== 0) {
      console.error(`\x1b[36m${prefix}\x1b[0m \x1b[31mProcess exited with code ${code}\x1b[0m`);
    }
  });

  return proc;
};

// メイン処理
const main = () => {
  const validWorkspaces = workspaces?.filter(isValidWorkspace);

  if (!validWorkspaces?.length) {
    console.error('\x1b[31mNo valid workspaces found with dev scripts\x1b[0m');
    return;
  }

  console.log('\x1b[32mStarting dev servers for valid workspaces...\x1b[0m');
  const processes = validWorkspaces.map(createProcess);

  process.on('SIGINT', () => {
    console.log('\n\x1b[33mGracefully shutting down...\x1b[0m');
    processes.forEach(proc => proc.kill());
    process.exit(0);
  });
};

main();
