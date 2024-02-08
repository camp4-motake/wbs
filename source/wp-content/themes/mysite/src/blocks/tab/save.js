import { InnerBlocks } from '@wordpress/block-editor';

export default function save({ attributes }) {
	const { blockIndex } = attributes;
	return (
		<div
			className="tab-panel entry-container"
			role="tabpanel"
			tabIndex="0"
			{...{
				':class': `{ 'active': activeTab === ${blockIndex} }`,
				'x-show.transition.in.opacity.duration.600': `activeTab === ${blockIndex}`,
			}}
		>
			<InnerBlocks.Content />
		</div>
	);
}
