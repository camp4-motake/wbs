<?php

/**
 * Title: 404
 * Slug: wbs/hidden-404
 * Categories: wbs-theme-patterns
 * Inserter: no
 */

?>
<!-- wp:group {"tagName":"main","style":{"spacing":{"blockGap":"var:preset|spacing|30","padding":{"top":"var:preset|spacing|xl","bottom":"var:preset|spacing|xl"}}},"className":"error404-section","layout":{"type":"constrained"}} -->
<main class="wp-block-group error404-section" style="padding-top:var(--wp--preset--spacing--xl);padding-bottom:var(--wp--preset--spacing--xl)">

	<!-- wp:group {"className":"container","layout":{"type":"constrained"}} -->
	<div class="wp-block-group container">

		<!-- wp:wbs/section-heading {"headContent":"<?php esc_html_e( '404', 'wbs' ); ?>","content":"<?php esc_html_e( 'Not Found', 'wbs' ); ?>","className":"is-style-obj-rd"} -->
		<div class="wp-block-wbs-section-heading is-style-obj-rd">
			<h2 class="head-content"><span><?php esc_html_e( '404', 'wbs' ); ?></span></h2>
			<div class="label-content"><?php esc_html_e( 'Not Found', 'wbs' ); ?></div>
		</div>
		<!-- /wp:wbs/section-heading -->

		<!-- wp:paragraph {"align":"center","className":"error404-description"} -->
		<p class="has-text-align-center error404-description"><?php esc_html_e( 'Sorry, but the page you are trying to view does not exist.', 'wbs' ); ?></p>
		<!-- /wp:paragraph -->

	</div>
	<!-- /wp:group -->

</main>
<!-- /wp:group -->
