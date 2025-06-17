<?php

/**
 * Title: post type Back Button
 * Slug: wbs/hidden-back-link-button
 * Categories: wbs-theme-patterns
 * Post Types: wp_template
 * Inserter: no
 */

$href = get_post_type_archive_link( get_post_type() );

?>
<!-- wp:separator {"className":"is-style-wide","style":{"spacing":{"margin":{"top":"var:preset|spacing|xl","bottom":"var:preset|spacing|lg"}}},"backgroundColor":"border-lighten"} -->
<hr class="wp-block-separator has-text-color has-border-lighten-color has-alpha-channel-opacity has-border-lighten-background-color has-background is-style-wide" style="margin-top:var(--wp--preset--spacing--xl);margin-bottom:var(--wp--preset--spacing--lg)" />
<!-- /wp:separator -->

<!-- wp:buttons {"className":"has-size-lg","layout":{"type":"flex","justifyContent":"center"},"customStyles":"has-size-lg"} -->
<div class="wp-block-buttons has-size-lg">
	<!-- wp:button {"className":"has-icon-arrow-prev","customStyles":"has-icon-arrow-prev"} -->
	<div class="wp-block-button has-icon-arrow-prev">
		<a class="wp-block-button__link wp-element-button" href="<?php echo esc_url( $href ); ?> "><?php esc_html_e( '一覧にもどる', 'wbs' ); ?></a>
	</div>
	<!-- /wp:button -->
</div>
<!-- /wp:buttons -->
