<?php

/**
 * Title: 404
 * Slug: wbs/hidden-404
 * Inserter: no
 */

?>
<!-- wp:group {"tagName":"main","style":{"spacing":{"blockGap":"var:preset|spacing|30","padding":{"top":"var:preset|spacing|xl","bottom":"var:preset|spacing|xl"}}},"className":"error404-section","layout":{"type":"constrained"}} -->
<main class="wp-block-group error404-section" style="padding-top:var(--wp--preset--spacing--xl);padding-bottom:var(--wp--preset--spacing--xl)">
	<!-- wp:group {"className":"container","layout":{"type":"constrained"}} -->
	<div class="wp-block-group container">
		<!-- wp:wbs/section-heading {"headContent":"<?php esc_html_e( 'Not Found', 'wbs' ); ?>","content":"404"} -->
		<h2 class="wp-block-wbs-section-heading"><small class="head-content"><?php esc_html_e( 'Not Found', 'wbs' ); ?></small><span class="label-content">404</span><span class="wing-bar"></span></h2>
		<!-- /wp:wbs/section-heading -->

		<!-- wp:paragraph {"align":"center","className":"error404-description"} -->
		<p class="has-text-align-center error404-description"><?php esc_html_e( 'Sorry, but the page you are trying to view does not exist.', 'wbs' ); ?></p>
		<!-- /wp:paragraph -->
	</div>
	<!-- /wp:group -->
</main>
<!-- /wp:group -->
