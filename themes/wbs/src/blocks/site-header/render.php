<?php

/**
 * Header
 *
 * @package wbs
 */

$wrapper_attribute = array(
	'class'               => 'header',
	'data-wp-interactive' => 'siteHeader',
	'data-site-header'    => '',
);

?>
<div <?php echo wp_kses_post( get_block_wrapper_attributes( $wrapper_attribute ) ); ?>>
	<div class="header-container">
		<a
			href="<?php echo esc_url( home_url() ); ?>"
			class="header-brand"
			title="<?php echo esc_attr( get_bloginfo( 'name' ) ); ?>">
			<span translate="no"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
		</a>
		<div class="header-actions"></div>
	</div>
</div>
