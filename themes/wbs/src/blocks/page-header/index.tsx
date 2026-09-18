import { __ } from '@wordpress/i18n';
import { registerThemeBlock } from '../../shared/registerThemeBlock';
import metadata from './block.json';
import Edit, { type PageHeaderAttributes } from './edit';
import './style.css';

registerThemeBlock< PageHeaderAttributes >( metadata.name, {
	title: __( 'ページヘッダー', 'wbs' ),
	edit: Edit,
	// 動的ブロック（render.php がフロントを描画）のため保存出力は持たない。
	save: () => null,
} );
