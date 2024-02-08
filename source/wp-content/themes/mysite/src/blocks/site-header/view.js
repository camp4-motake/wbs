import { MQ } from '../../main/constants';

const ignoreCloseSelector = [
	'.nav-primary',
	'.menu-toggle',
	'[data-menu-close-ignore]',
].join(',');
const breakpoint = MQ.lg;

document.addEventListener('alpine:init', () => {
	const { Alpine } = window;

	Alpine.data('menuToggle', () => ({
		init() {
			this.addMatchMediaEvent();
			this.addOuterClickEvent();
		},

		toggle: {
			'@click'() {
				this.$store.menuStatus.shown = !this.$store.menuStatus.shown;
			},

			'@menu:close.window'() {
				this.close();
			},

			':title'() {
				return this.$store.menuStatus.shown
					? 'Menu Close'
					: 'Menu Open';
			},

			':aria-expanded'() {
				return this.$store.menuStatus.shown;
			},

			':data-menu-toggle'() {
				return this.$store.menuStatus.shown ? 'shown' : 'close';
			},
		},

		menuLabel: {
			'x-text'() {
				return this.$store.menuStatus.shown ? 'Close' : 'Menu';
			},
		},

		addMatchMediaEvent() {
			window.matchMedia(breakpoint).addEventListener('change', () => {
				this.$dispatch('menu:close');
			});
		},

		addOuterClickEvent() {
			document.addEventListener('click', (event) => {
				const { target } = event;
				if (target?.closest(ignoreCloseSelector)) return;
				this.close();
			});
		},

		close() {
			this.$store.menuStatus.shown = false;
		},
	}));

	/**
	 * close
	 */
	Alpine.data('menuClose', () => ({
		menuClose: {
			'@click'() {
				this.$store.menuStatus.shown = false;
			},
		},
	}));
});
