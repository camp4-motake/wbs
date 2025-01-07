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
);

?>
<div <?php echo wp_kses_post( get_block_wrapper_attributes( $wrapper_attribute ) ); ?>>
	<div class="header-container">
		<a
			href="<?php echo esc_url( home_url() ); ?>"
			class="header-brand"
			title=" <?php echo esc_html( get_bloginfo( 'name' ) ); ?>">
			<span class="visually-hidden"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
			<span aria-hidden="true" role="presentation">
				<?php echo wp_kses( Image\inline_svg( 'build/images/logo-brand.svg' ), Image\kses_svg() ); ?>
			</span>
		</a>
		<nav class="header-nav">
			<!-- nav -->
		</nav>
		<button type="button"
			class="menu-toggle"
			data-menu-close-ignore
			data-wp-init="callbacks.init"
			data-wp-bind--aria-expanded="state.menu.shown"
			data-wp-bind--title="callbacks.menu.title"
			data-wp-on--click="actions.menu.toggle"
			data-wp-on-window--store-menu-close="actions.menu.close">
			<span class="toggle-image is-toggle" role="presentation"><?php echo wp_kses( Image\inline_svg( 'build/images/menu-toggle.svg' ), Image\kses_svg() ); ?></span>
			<span class="toggle-image is-close" role="presentation"><?php echo wp_kses( Image\inline_svg( 'build/images/menu-close.svg' ), Image\kses_svg() ); ?></span>
		</button>
	</div>
</div>
<div
	data-header-trigger
	data-wp-interactive="siteHeaderInViewObserver"
	data-wp-init="callbacks.init"></div>
<div data-modal-overlay></div>

<?php get_template_part( 'src/blocks/site-header/assets/nav-modal' ); ?>