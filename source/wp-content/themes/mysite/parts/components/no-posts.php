<?php

$ctx = wp_parse_args( $args, array( 'no_post_label' => 'coming soon' ) );

?>
<p class="text-center">
	<small class="roundLabel"><?php echo esc_html( $ctx['no_post_label'] ); ?></small>
</p>
