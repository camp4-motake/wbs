/**
 * Shared slider module (`@wbs/slider`).
 *
 * Bundles embla-carousel and its class-names plugin into a single WordPress
 * Script Module so every slider-using block can share one runtime copy.
 * Framework-agnostic on purpose: it does NOT depend on `@wordpress/interactivity`.
 * Consuming blocks reference it lazily from their view module:
 *
 *   const { createSlider } = await import( /* webpackIgnore: true *\/ '@wbs/slider' );
 *
 * and must enqueue it in PHP (`wp_enqueue_script_module( '@wbs/slider' )`) so the
 * specifier resolves through WordPress' import map.
 */

import EmblaCarousel, {
	type EmblaCarouselType,
	type EmblaOptionsType,
	type EmblaPluginType,
} from 'embla-carousel';
import ClassNames, {
	type ClassNamesOptionsType,
} from 'embla-carousel-class-names';

/** Options accepted by {@link createSlider}. */
export type CreateSliderOptions = {
	/** Embla options passed through verbatim. */
	embla?: EmblaOptionsType;
	/**
	 * Class-names plugin options. Defaults to enabled; pass `false` to opt out.
	 */
	classNames?: ClassNamesOptionsType | false;
	/** Extra embla plugins (e.g. autoplay) appended after the built-in ones. */
	plugins?: EmblaPluginType[];
	/** `aria-label` for the carousel root (viewport). */
	carouselLabel?: string;
	/** Builds each slide's `aria-label`. Defaults to `"{n} / {total}"`. */
	slideLabel?: ( index: number, total: number ) => string;
};

/**
 * Initialise an embla carousel on `viewport` with the theme's shared defaults.
 *
 * `viewport` is the `[data-slider-viewport]` element (the overflow/clipping
 * element). The theme's markup contract is resolved by default:
 * `[data-slider-viewport] > [data-slider-container] > [data-slider-slide]*`.
 * Override via `options.embla.container` / `options.embla.slides`.
 *
 * @param viewport Embla root element (`[data-slider-viewport]`).
 * @param options  Slider options.
 * @return The embla API instance (`.destroy()`, `.goToNext()`, …), or
 *         `undefined` when `viewport` is missing.
 */
export function createSlider(
	viewport: HTMLElement | null | undefined,
	options: CreateSliderOptions = {}
): EmblaCarouselType | undefined {
	if ( ! viewport ) {
		return undefined;
	}

	const prefersReducedMotion =
		window.matchMedia?.( '(prefers-reduced-motion: reduce)' ).matches ??
		false;

	const emblaOptions: EmblaOptionsType = {
		// Resolve the theme's data-attribute markup contract by default.
		container: '[data-slider-container]',
		slides: '[data-slider-slide]',
		...options.embla,
	};
	if ( prefersReducedMotion ) {
		// Snap instantly instead of animating.
		emblaOptions.duration = 0;
	}

	const plugins: EmblaPluginType[] = [];
	if ( options.classNames !== false ) {
		plugins.push( ClassNames( options.classNames || undefined ) );
	}
	if ( options.plugins ) {
		plugins.push( ...options.plugins );
	}

	const embla = EmblaCarousel( viewport, emblaOptions, plugins );

	applyAria( embla, viewport, options );

	return embla;
}

// Apply the minimal WAI-ARIA carousel wiring embla does not provide.
function applyAria(
	embla: EmblaCarouselType,
	viewport: HTMLElement,
	options: CreateSliderOptions
): void {
	if ( ! viewport.getAttribute( 'role' ) ) {
		viewport.setAttribute( 'role', 'group' );
	}
	viewport.setAttribute( 'aria-roledescription', 'carousel' );
	if ( options.carouselLabel ) {
		viewport.setAttribute( 'aria-label', options.carouselLabel );
	}

	const slides = embla.slideNodes();
	const total = slides.length;
	const label =
		options.slideLabel ??
		( ( index, count ) => `${ index + 1 } / ${ count }` );

	slides.forEach( ( slide, index ) => {
		slide.setAttribute( 'role', 'group' );
		slide.setAttribute( 'aria-roledescription', 'slide' );
		slide.setAttribute( 'aria-label', label( index, total ) );
	} );
}

// Escape-hatch re-exports for blocks that need the raw primitives.
export { EmblaCarousel, ClassNames };
export type {
	EmblaCarouselType,
	EmblaOptionsType,
	EmblaPluginType,
	ClassNamesOptionsType,
};
