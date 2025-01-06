import { registerBlockType } from '@wordpress/blocks';
import metadata from './block.json';
import Edit from './edit';
import save from './save';

import './style.css';

const { name } = metadata;

registerBlockType( name, {
	edit: Edit,
	save,
} );
