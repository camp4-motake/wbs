import { InnerBlocks } from '@wordpress/block-editor';
import { registerBlockType } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import metadata from './block.json';
import Edit from './edit';
import './style.css';

registerBlockType( metadata.name, {
	title: __( 'ページヘッダー', 'wbs' ),
	edit: Edit,
	save: () => <InnerBlocks.Content />,
} );
