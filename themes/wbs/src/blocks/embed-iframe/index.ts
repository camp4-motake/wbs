import { __ } from '@wordpress/i18n';
import { registerThemeBlock } from '../../shared/registerThemeBlock';
import metadata from './block.json';
import Edit, { type EmbedIframeAttributes } from './edit';
import './style.css';

registerThemeBlock< EmbedIframeAttributes >( metadata.name, {
	title: __( 'Raw iFrame', 'wbs' ),
	edit: Edit,
} );
