<?php

use Site\THeme\Helper\Path;
use Site\THeme\Helper\Util;

$ctx = wp_parse_args(
	$args,
	array(
		'class' => '',
		'align' => '',
	)
);

$class_name = 'share-links';
if ( $ctx['class'] ) {
	$class_name .= ' ' . $ctx['class'];
}
if ( $ctx['align'] ) {
	$class_name .= ' -' . $ctx['align'];
}

$links = Util\get_share_links();
$items = array(
	'x'        => 'X（旧Twitter）',
	'facebook' => 'Facebook',
);

?>
<div class="<?php echo esc_attr( $class_name ); ?>">
	<span class="share-links-label">SHARE</span>
	<?php foreach ( $items as $k => $label ) : ?>
		<a href="<?php echo esc_url( $links[ $k ] ); ?>" title="<?php echo esc_attr( $label ); ?>" class="share-link" target="_blank" rel="noopener noreferrer">
			<img src="<?php echo esc_url( Path\cache_buster( 'build/images/sns-' . esc_attr( $k ) . '.svg' ) ); ?>" width="48" height="48" alt="<?php echo esc_attr( $v['label'] ); ?>" decoding="async">
		</a>
	<?php endforeach; ?>
</div>
