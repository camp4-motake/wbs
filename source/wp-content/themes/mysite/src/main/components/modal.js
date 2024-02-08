import Alpine from 'alpinejs';
import { sleep } from '../utils/sleep';

Alpine.data('modal', () => ({
	open: false,
	contentVisible: false,

	modal_dialog: {
		'x-init'() {
			this.$watch('open', (val) => {
				if (!val) sleep(100).then(() => this.$el.scrollTo(0, 0));
			});
		},
		'x-show'() {
			return this.open;
		},
		'x-transition:enter'() {
			return '-enter';
		},
		'x-transition:enter-start'() {
			return '-enter-start';
		},
		'x-transition:enter-end'() {
			return '-enter-end';
		},
		'x-transition:leave'() {
			return '-leave';
		},
		'x-transition:leave-start'() {
			return '-leave-start';
		},
		'x-transition:leave-end'() {
			return '-leave-end';
		},
	},

	modal_content: {
		'@click.outside'() {
			this.open = false;
		},
		':class'() {
			return { '-show': this.open };
		},
		':role'() {
			return this.open ? 'dialog' : '';
		},
		':aria-modal'() {
			return this.open;
		},
	},

	modal_trigger: {
		'@click'() {
			this.open = !this.open;
		},
	},

	modal_close: {
		'@click'() {
			this.open = false;
		},
	},
}));
