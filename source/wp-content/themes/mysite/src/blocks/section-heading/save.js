import { RichText, useBlockProps } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { level, headContent, content } = attributes;
	const Tag = 'h' + level;
	const blockProps = useBlockProps.save();

	return (
		<div { ...blockProps }>
			<Tag class="head-content">
				<RichText.Content tagName="span" value={ headContent } />
			</Tag>
			<RichText.Content
				className={ 'label-content' }
				tagName="div"
				value={ content }
			/>
		</div>
	);
}
