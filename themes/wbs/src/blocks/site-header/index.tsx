import { InnerBlocks } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { registerThemeBlock } from '../../shared/registerThemeBlock';
import metadata from './block.json';
import Edit from './edit';
import './style.css';

registerThemeBlock( metadata.name, {
	title: __( 'サイトヘッダー', 'wbs' ),
	edit: Edit,
	save: () => <InnerBlocks.Content />,
} );
