<?php

/**
 * acf block sample
 */

?>
<p <?php echo esc_attr( get_block_wrapper_attributes() ); ?>>
<?php echo get_field( 'text' ); ?>
</p>
