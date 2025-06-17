<?php

/**
 * site menu toggle button
 *
 * @package wbs
 */

use Site\Theme\Helper\Image;

?>
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
