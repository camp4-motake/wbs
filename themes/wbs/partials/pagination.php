<?php

/**
 * pagination
 *
 * @package wbs
 */

use Site\Theme\Helper\Paging;

$ctx        = wp_parse_args( array( 'custom_query' => null ) );
$pagination = Paging\custom_pagination( true, $ctx['custom_query'] );

?>
<div class="pagination-scroller">
	<?php
	if ( $pagination ) {
		echo wp_kses_post( $pagination );
	}
	?>
</div>
<?php
/*
	// test
	<ul class="pagination">
		<li class="pagination-item"><a class="prev page-numbers" href="#0"><span>前へ</span></a></li>
		<li class="pagination-item is-rwd">
			<a class="page-numbers" href="#0">1</a>
		</li>
		<li class="pagination-item is-rwd">
			<span class="page-numbers dots">…</span>
		</li>
		<li class="pagination-item is-rwd"><a class="page-numbers" href="#0">3</a></li>
		<li class="pagination-item is-rwd">
			<a class="page-numbers" href="#0">4</a>
		</li>
		<li class="pagination-item">
			<span aria-current="page" class="page-numbers current">5</span>
		</li>
		<li class="pagination-item is-rwd">
			<a class="page-numbers" href="#0">6</a>
		</li>
		<li class="pagination-item">
			<a class="next page-numbers" href="#0"><span>次へ</span></a>
		</li>
	</ul>
	*/
?>
