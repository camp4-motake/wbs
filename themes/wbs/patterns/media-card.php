<?php

/**
 * Title: Media Card
 * Slug: wbs/media-card
 * Categories: wbs-theme-patterns
 */

?>
<!-- wp:media-text {"align":"wide","mediaWidth":30,"verticalAlignment":"top","className":"is-style-media-card","borderColor":"border-lighten"} -->
<div class="wp-block-media-text alignwide is-stacked-on-mobile is-vertically-aligned-top is-style-media-card has-border-color has-border-lighten-border-color" style="grid-template-columns:30% auto">
	<figure class="wp-block-media-text__media"></figure>
	<div class="wp-block-media-text__content">
		<!-- wp:heading {"level":3,"className":"is-style-default"} -->
		<h3 class="wp-block-heading is-style-default"></h3>
		<!-- /wp:heading -->

		<!-- wp:paragraph -->
		<p></p>
		<!-- /wp:paragraph -->

		<!-- wp:buttons {"className":"is-style-under-line","layout":{"type":"flex","justifyContent":"right"}} -->
		<div class="wp-block-buttons is-style-under-line">
			<!-- wp:button -->
			<div class="wp-block-button"><a class="wp-block-button__link wp-element-button">MORE</a></div>
			<!-- /wp:button -->
		</div>
		<!-- /wp:buttons -->
	</div>
</div>
<!-- /wp:media-text -->
