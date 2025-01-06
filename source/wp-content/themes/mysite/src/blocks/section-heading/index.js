import { registerBlockType } from '@wordpress/blocks';

import metadata from './block.json';
import Edit from './edit';
import save from './save';
import './style.css';

const { name, title, description, category, attributes } = metadata;

registerBlockType( name, {
	title,
	description,
	category,
	attributes,

	edit: Edit,
	save,
} );
