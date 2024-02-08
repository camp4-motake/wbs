<?php

/**
 * Footer
 *
 * @package wbs
 */

use Site\Theme\Helper\Image;

$attr = array(
	'class'  => 'footer',
	'x-bind' => 'footer_trigger',
);

?>
<div <?php echo wp_kses_post( get_block_wrapper_attributes( $attr ) ); ?>>
	<div class="footer-body">
		<a href="<?php echo esc_url( home_url() ); ?>" title=" <?php echo esc_html( get_bloginfo( 'name' ) ); ?>">
			<span><?php echo esc_html( get_bloginfo( 'name' ) ); ?></span>
		</a>
		<?php get_template_part( 'src/blocks/site-footer/copyright', null, array( 'class' => 'container -max-lg' ) ); ?>
	</div>
</div>
