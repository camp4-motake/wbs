import intersect from '@alpinejs/intersect';
import Alpine from 'alpinejs';
import './components/app';
import './components/modal';
import './stores/menuStatus';
import './stores/siteStatus';

import './index.css';

window.Alpine = Alpine;
Alpine.plugin(intersect);

document.addEventListener('DOMContentLoaded', () => {
	document.documentElement.setAttribute('x-data', 'app');
	document.documentElement.setAttribute('x-bind', 'root');
	window.Alpine.start();
});
