<?php

/**
 * Adobe Fonts
 *
 * @package wbs
 */

namespace Site\Theme\AdobeFonts;

define(
	'ADOBE_FONT_TAG',
	<<<EOM

	(function(d) {
		var config = {
			kitId: '*****',
			scriptTimeout: 3000,
			async: true
		},
		h=d.documentElement,t=setTimeout(function(){h.className=h.className.replace(/\bwf-loading\b/g,"")+" wf-inactive";},config.scriptTimeout),tk=d.createElement("script"),f=false,s=d.getElementsByTagName("script")[0],a;h.className+=" wf-loading";tk.src='https://use.typekit.net/'+config.kitId+'.js';tk.async=true;tk.onload=tk.onreadystatechange=function(){a=this.readyState;if(f||a&&a!="complete"&&a!="loaded")return;f=true;clearTimeout(t);try{Typekit.load(config)}catch(e){}};s.parentNode.insertBefore(tk,s)
	})(document);

EOM
);

/**
 * insert frontend
 */
function add_head_font_tag() {
	$_script = ADOBE_FONT_TAG;
	echo "\n<script>{$_script}</script>\n"; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
}
add_action( 'wp_head', __NAMESPACE__ . '\\add_head_font_tag', 100 );

/**
 * insert block editor
 */
function enqueue_editor_adobe_fonts() {
	$_script = ADOBE_FONT_TAG;
	$_script = "\n window.addEventListener('load', () => { {$_script} }); \n"; // WORKAROUND
	wp_add_inline_script( 'theme-block-styles-scripts', $_script, 'after' );
}
add_action( 'enqueue_block_assets', __NAMESPACE__ . '\\enqueue_editor_adobe_fonts', 200 );
