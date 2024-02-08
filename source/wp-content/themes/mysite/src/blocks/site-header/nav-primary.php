<?php

/**
 * Nav primary modal
 *
 * @package wbs
 */

use Site\Theme\Helper\Nav;

?>
<nav class="nav-modal">
	<div class="nav-modal-bg" role="presentation">
		<?php
		/*
		<div class="nav-modal-bg-svg"><?php echo wp_kses( Image\inline_svg( 'build/images/ship.svg' ), Image\kses_svg() ); ?></div>
		 */
		?>
	</div>
	<div class="nav-modal-body">
		<div class="nav-modal-section">
			<ul class="nav-primary">
				<?php echo wp_kses_post( Nav\make_flat_menu_html( 'nav-primary' ) ); ?>
			</ul>
		</div>
		<div class="nav-modal-section">
			<ul class="nav-secondary">
				<?php echo wp_kses_post( Nav\make_flat_menu_html( 'nav-primary-sub' ) ); ?>
			</ul>
		</div>
	</div>
</nav>
