<?php

/**
 * Header
 *
 * @package wbs
 */

use Site\Theme\Helper\Image;
use Site\Theme\Helper\Nav;

$attr = array( 'class' => 'header' );

?>
<div <?php echo wp_kses_post( get_block_wrapper_attributes( $attr ) ); ?>>
	<div class="header-container">
		<a href="<?php echo esc_url( home_url() ); ?>" class="header-brand" title=" <?php echo esc_html( get_bloginfo( 'name' ) ); ?>">
			<span><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
		</a>
		<nav class="header-nav">
			<ul class="nav-primary">
				<?php echo wp_kses_post( Nav\make_flat_menu_html( 'nav-primary' ) ); ?>
			</ul>
		</nav>
		<div class="header-nav -secondary">
			<ul class="nav-secondary"><?php echo wp_kses_post( Nav\make_flat_menu_html( 'nav-primary-sub' ) ); ?></ul>
		</div>
		<button type="button" class="menu-toggle" title="Menu Open" x-data="menuToggle" x-bind="toggle">
			<i class="toggle-image -toggle" role="presentation"><?php echo wp_kses( Image\inline_svg( 'build/images/menu-toggle.svg' ), Image\kses_svg() ); ?></i>
			<i class="toggle-image -close" role="presentation"><?php echo wp_kses( Image\inline_svg( 'build/images/menu-close.svg' ), Image\kses_svg() ); ?></i>
		</button>
	</div>
</div>

<div data-header-trigger x-bind="header_trigger"></div>

<div data-modal-overlay></div>

<?php get_template_part( 'src/blocks/site-header/nav-primary' ); ?>

<?php get_template_part( 'src/blocks/site-header/go-top.php' ); ?>
