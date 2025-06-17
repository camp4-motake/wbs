import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { link } = attributes;
	const blockProps = useBlockProps.save( {
		className: 'swiper-slide',
	} );
	const slideContent = <InnerBlocks.Content />;

	return link?.url ? (
		<a
			{ ...blockProps }
			href={ link.url }
			target={ link.target }
			rel={ link.rel }
			title={ link.title }
		>
			{ slideContent }
		</a>
	) : (
		<div { ...blockProps }>{ slideContent }</div>
	);
}
