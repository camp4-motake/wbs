import { InnerBlocks } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { registerThemeBlock } from '../../shared/registerThemeBlock';
import metadata from './block.json';
import Edit, { type ArticleLoopAttributes } from './edit';
import './style.css';

registerThemeBlock< ArticleLoopAttributes >( metadata.name, {
	title: __( '記事ループ', 'wbs' ),
	edit: Edit,
	save: () => <InnerBlocks.Content />,
} );
