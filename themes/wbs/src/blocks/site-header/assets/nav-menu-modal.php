<?php

/**
 * Nav primary modal
 *
 * @package wbs
 */

?>
<nav class="nav-modal">
	<div class="nav-modal-scroller">
		<div class="nav-modal-scroller__inner" data-menu-close-ignore>
			<!-- nav inner -->
			<?php

			if ( has_nav_menu( 'nav_header' ) ) {
				$menu_args = array(
					'theme_location' => 'nav_header',
					'menu_class'     => 'nav-menu',
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
		</div>
	</div>
</nav>