import { initAnchorScroll } from './anchorScroll';
import { ariaCurrent } from './ariaCurrent';
import { domReady } from './domReady';
import { externalLinks } from './externalLink';
import { pageActive } from './pageActive';
import { scrollbarSize } from './scrollbarSize';
import { sleep } from './sleep';
import { wpAdminBarSize } from './wpAdminBarSize';

const main = async () => {
	scrollbarSize();
	await domReady();
	ariaCurrent();
	externalLinks();
	initAnchorScroll();
	wpAdminBarSize();
	await sleep( 100 );
	pageActive();
};

main();
