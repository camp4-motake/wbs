<?php

/**
 * Header
 *
 * @package wbs
 */

use Site\Theme\Helper\Image;

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
			class="header-global-brand"
			title=" <?php echo esc_html( home_url() ); ?>"
			target="_blank">
			<span class="visually-hidden"><?php echo esc_html( home_url() ); ?></span>
			<span role="presentation"><?php echo wp_kses( Image\inline_svg( 'build/images/logo-brand.svg' ), Image\kses_svg() ); ?></span>
		</a>
		<div class="header-titles">
			<a
				href="<?php echo esc_url( home_url() ); ?>"
				class="header-brand"
				title=" <?php echo esc_html( get_bloginfo( 'name' ) ); ?>">
				<span class="visually-hidden"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
				<span role="presentation">
					<span class="header-brand-logo has-display-sm"><?php echo wp_kses( Image\inline_svg( 'build/images/logo-brand-small.svg' ), Image\kses_svg() ); ?></span>
					<span class="header-brand-logo has-display-lg"><?php echo wp_kses( Image\inline_svg( 'build/images/logo-brand.svg' ), Image\kses_svg() ); ?></span>
				</span>
			</a>
		</div>
		<div class="header-actions"></div>
	</div>
</div>