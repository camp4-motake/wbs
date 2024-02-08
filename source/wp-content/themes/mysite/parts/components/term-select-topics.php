<div class="entry-container alignWide archive-category-select">
	<div class="archive-category-select-box is-right">
		<?php

		get_template_part(
			'parts/components/term-select',
			null,
			array(
				'post_type' => 'topics',
				'tax'       => 'category-topics',
			)
		);

		?>
	</div>
</div>
