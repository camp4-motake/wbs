import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import metadata from './block.json';
import Edit from './edit';
import save from './save';

registerBlockType( metadata.name, {
	title: __( '定義リストアイテム', 'wbs' ),
	edit: Edit,
	save,
} );
