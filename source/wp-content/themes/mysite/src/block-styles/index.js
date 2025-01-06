import domReady from '@wordpress/dom-ready';
import { blockStyles } from './blockStyles';
import { blockStylesExtension } from './blockStylesExtension';
import { blockVariations } from './blockVariations';
import { formattingToolbar } from './formattingToolbar';
import './index.css';

domReady( () => {
	blockStyles();
	blockVariations();
	formattingToolbar();
	blockStylesExtension();
} );
