import domReady from '@wordpress/dom-ready';
import { blockStyles, removeBlockStyles } from './blockStyles';
import { blockStylesExtension } from './blockStylesExtension';
import './index.css';

domReady( () => {
	blockStyles();
	blockStylesExtension();
} );

window.onload = () => {
	removeBlockStyles();
};
