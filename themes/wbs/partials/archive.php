<?php

/**
 * archive
 *
 * @package wbs
 */

get_template_part(
	'partials/main-loop-grid',
	null,
	array( 'class' => 'has-post-type-' . get_post_type() )
);
