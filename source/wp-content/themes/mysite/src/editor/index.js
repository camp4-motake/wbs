import { acfFilter } from './acfFilter';
import { allowedBlock } from './allowedBlock';
import { blockStyles } from './blockStyles';
import { blockVariations } from './blockVariations';
import './index.css';

window.addEventListener( 'load', () => {
	acfFilter();
	allowedBlock();
	blockStyles();
	blockVariations();
} );
