import { InnerBlocks } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';
import { registerThemeBlock } from '../../shared/registerThemeBlock';
import metadata from './block.json';
import Edit, { type SiteFooterAttributes } from './edit';
import './style.css';

registerThemeBlock< SiteFooterAttributes >( metadata.name, {
	title: __( 'サイトフッター', 'wbs' ),
	edit: Edit,
	save: () => <InnerBlocks.Content />,
} );
