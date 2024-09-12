import Alpine from 'alpinejs';

Alpine.data( 'app', () => ( {
	interval: 0,
	isRecaptcha: false,
	scrollBarWidth: 0,
	wpAdminBar: null,

	init() {
		this.$nextTick( () => {
			this.$store.siteStatus.isPageActive = true;
			this.scrollBarCheckInterval();
			this.setWPMatchMediaEvent();
		} );
	},

	root: {
		':class'() {
			return {
				'is-dialog-open': this.$store.siteStatus.isDialogOpen,
				'is-menu-shown': this.$store.menuStatus.shown,
				'is-page-active': this.$store.siteStatus.isPageActive,
				'is-scroll-down':
					this.$store.siteStatus.isScrollDown &&
					! this.$store.menuStatus.shown,
				'is-scroll-bottom-in': this.$store.siteStatus.isScrollBottomIn,
			};
		},
		':style'() {
			return {
				'--window-scroll-bar-width': `${ this.scrollBarWidth }px`,
			};
		},
		'@resize.window'() {
			this.setWPAdminBarSize();
		},
	},

	header_trigger: {
		'x-intersect:enter'() {
			this.$store.siteStatus.isScrollDown = false;
		},
		'x-intersect:leave'() {
			this.$store.siteStatus.isScrollDown = true;
		},
	},

	footer_trigger: {
		'x-intersect:enter'() {
			this.$store.siteStatus.isScrollBottomIn = true;
		},
		'x-intersect:leave'() {
			this.$store.siteStatus.isScrollBottomIn = false;
		},
	},

	goTop( event ) {
		event.preventDefault();
		window.scroll( { top: 0, behavior: 'smooth' } );
	},

	scrollBarCheckInterval() {
		this.interval = window.setInterval( () => {
			this.setScrollbarWidth();
		}, 1000 );
	},

	setScrollbarWidth() {
		this.scrollBarWidth =
			window.innerWidth - document.documentElement.clientWidth;
	},

	setWPMatchMediaEvent() {
		this.wpAdminBar = document.getElementById( 'wpadminbar' );
		if ( ! this.wpAdminBar ) return;
		const mq = window.matchMedia( 'screen and (max-width: 782px)' );
		mq.addEventListener( 'change', () => this.setWPAdminBarSize() );
		this.setWPAdminBarSize();
	},

	setWPAdminBarSize() {
		if ( ! this.wpAdminBar ) return;
		const size = this.wpAdminBar.getBoundingClientRect();
		this.$el.style.setProperty(
			'--wp-adminbar-height',
			`${ size.height }px`
		);
	},
} ) );
