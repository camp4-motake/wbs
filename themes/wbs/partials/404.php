<?php

/**
 * 404
 *
 * @package wbs
 */

$response_code = http_response_code();

?>
<div class="error404-section">
	<div class="error404-content">
		<hgroup class="error404-heading">
			<h1 class="error404-heading-status"><?php echo esc_html( $response_code ); ?></h1>
			<p class="error404-heading-label"><?php esc_html_e( 'Not Found', 'wbs' ); ?></p>
		</hgroup>
		<p class="error404-description">
			<?php esc_html_e( 'Sorry, but the page you are trying to view does not exist.', 'wbs' ); ?>
		</p>
		<div class="btn-wrapper">
			<a href="<?php echo esc_url( home_url() ); ?>">
				<span><?php esc_html_e( 'Back to Home', 'wbs' ); ?> </span>
			</a>
		</div>
	</div>
</div>
