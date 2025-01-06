import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import clsx from 'clsx';
import './style.css';

export default function save( { attributes } ) {
	const { isShadow, isSingleOpen } = attributes;
	const blockProps = useBlockProps.save( {
		'data-wp-interactive': 'accordion',
		'data-wp-init': 'callbacks.init',
		'data-wp-context': JSON.stringify( { isSingleOpen } ),
		className: clsx( { 'is-style-shadow': isShadow } ),
	} );

	return (
		<div { ...blockProps }>
			<InnerBlocks.Content />
		</div>
	);
}
