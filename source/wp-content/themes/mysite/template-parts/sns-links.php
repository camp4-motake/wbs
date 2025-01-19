<?php

$ctx = wp_parse_args( $args, array( 'exclude_prefix' => array() ) );

$sns_links = array();

if ( defined( 'WBS_SNS_LINKS' ) ) {
	$sns_links = WBS_SNS_LINKS;
}

if ( count( $sns_links ) <= 0 ) {
	return;
}

?>
<ul class="sns-links">
	<?php
	foreach ( $sns_links as $key => $sns ) :
		if ( in_array( $key, $ctx['exclude_prefix'], true ) ) {
			continue;
		}
		?>
		<li>
			<a
				href="<?php echo esc_url( $sns['href'] ); ?>"
				class="sns-link <?php echo esc_attr( 'is-ico-' . $key ); ?>"
				target="_blank"
				rel="noopener noreferrer">
				<span class="visually-hidden"><?php echo wp_kses_post( $sns['label'] ); ?></span>
			</a>
		</li>
	<?php endforeach; ?>
</ul>