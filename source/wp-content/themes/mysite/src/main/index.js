import intersect from '@alpinejs/intersect';
import Alpine from 'alpinejs';
import './components/app';
import './components/modal';
import './stores/menuStatus';
import './stores/siteStatus';

import './index.css';

Alpine.plugin( intersect );
window.Alpine = Alpine;
window.Alpine.start();
