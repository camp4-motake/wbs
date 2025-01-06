import { RichText, useBlockProps } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const Tag = 'h' + attributes.level;
	const blockProps = useBlockProps.save( {
		className: `has-text-${ attributes.alignment }`,
	} );

	return (
		<Tag { ...blockProps }>
			<RichText.Content
				className={ 'shoulder-content' }
				tagName="small"
				value={ attributes.shoulderContent }
			/>
			<RichText.Content
				className={ 'label-content' }
				tagName="span"
				value={ attributes.content }
			/>
		</Tag>
	);
}
