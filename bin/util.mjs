import { findWorkspaces } from 'find-workspaces';
import { relative } from 'node:path';

export const workspaces = findWorkspaces()?.map( ( ws ) =>
	relative( process.cwd(), ws?.location )
);
