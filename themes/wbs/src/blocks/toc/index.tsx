import { __ } from '@wordpress/i18n';

import { registerThemeBlock } from '../../shared/registerThemeBlock';
import metadata from './block.json';
import Edit, { type TocAttributes } from './edit';
import './style.css';

registerThemeBlock< TocAttributes >( metadata.name, {
	title: __( '目次', 'wbs' ),
	description: __( '投稿内の見出しから目次を自動生成します', 'wbs' ),
	edit: Edit,
	// 動的ブロック（render.php でサーバーサイド描画）のため save は出力なし。
	save: () => null,
} );
