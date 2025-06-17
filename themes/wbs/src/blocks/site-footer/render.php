<?php

/**
 * Footer
 *
 * @package wbs
 */

$wrapper_attribute = array(
	'class'            => 'footer',
	'data-site-footer' => '',
);

?>
<div <?php echo wp_kses_post( get_block_wrapper_attributes( $wrapper_attribute ) ); ?>>
	<div class="footer-body">
		<div class="footer-content">
			<a class="footer-brand" href="<?php echo esc_url( home_url() ); ?>" title="<?php echo esc_html( get_bloginfo( 'name' ) ); ?>">
				<span translate="no"><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
			</a>
			<nav>
				<?php

				if ( has_nav_menu( 'nav_footer' ) ) {
					$menu_args = array(
						'theme_location' => 'nav_footer',
						'menu_class'     => 'footer-nav-list',
						'menu_id'        => '',
						'container'      => false,
						'link_before'    => '',
						'link_after'     => '',
						'fallback_cb'    => false,
						'depth'          => 2,
					);
					wp_nav_menu( $menu_args );
				}

				?>
			</nav>
		</div>
		<div class="footer-aside">
		</div>
	</div>
	<?php get_template_part( 'src/blocks/site-footer/copyright' ); ?>
</div>