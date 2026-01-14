import domReady from '@wordpress/dom-ready';
import { blockStyles, removeBlockStyles } from './blockStyles';
import { blockStylesExtension } from './blockStylesExtension';

domReady( () => {
	blockStyles();
	blockStylesExtension();
} );

window.onload = () => {
	removeBlockStyles();
};
