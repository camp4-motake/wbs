import { initAnchorScroll } from './anchorScroll';
import { setAriaCurrent } from './ariaCurrent';
import { domReady } from './domReady';
import { inView } from './inView';
import { pageActive } from './pageActive';
import { sleep } from './sleep';
import { wpAdminBarSize } from './wpAdminBarSize';

const main = async () => {
	await domReady();
	inView();
	initAnchorScroll();
	setAriaCurrent();
	wpAdminBarSize();

	await sleep( 100 );
	pageActive();
};

main();
