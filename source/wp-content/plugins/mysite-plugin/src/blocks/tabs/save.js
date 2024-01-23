import { __ } from '@wordpress/i18n';
import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
const { RawHTML } = wp.element;

export default function save({ attributes }) {
	const { tabLabelsArray, sideTabLayout } = attributes;
	const blockProps = sideTabLayout
		? useBlockProps.save({ className: 'side-tab-layout' })
		: useBlockProps.save();

	return (
		<div {...blockProps}>
			<ul
				className="tab-labels"
				role="tablist"
				aria-label="tabbed content"
			>
				{tabLabelsArray.map((label, i) => {
					return (
						<li
							key={'tab-' + i}
							className={
								i === 0 ? 'tab-label active' : 'tab-label'
							}
							role="tab"
							aria-selected={i === 0 ? 'true' : 'false'}
							aria-controls={label}
							tabIndex="0"
						>
							<RawHTML>{label}</RawHTML>
						</li>
					);
				})}
			</ul>
			<div className="tab-content">
				<InnerBlocks.Content />
			</div>
		</div>
	);
}
