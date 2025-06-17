import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import metadata from './block.json';
import Edit from './edit';
import save from './save';
import './style.css';

registerBlockType( metadata.name, {
	title: __( 'グリッドカラムコンテンツ', 'wbs' ),
	description: __( '', 'wbs' ),
	edit: Edit,
	save,
} );
