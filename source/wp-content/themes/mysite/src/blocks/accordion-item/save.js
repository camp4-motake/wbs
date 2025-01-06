import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';

export default function save( { attributes } ) {
	const { summaryLabel, itemID, isInitialOpen } = attributes;
	const rootID = `accordion-root-${ itemID }`;
	const triggerID = `accordion-${ itemID }`;
	const controlID = `accordion-section-${ itemID }`;

	const blockProps = useBlockProps.save( {
		id: rootID,
		open: isInitialOpen,
	} );

	return (
		<details { ...blockProps }>
			<summary
				type="button"
				id={ triggerID }
				className="accordion-summary"
				data-js-initial-open={ isInitialOpen }
			>
				<span className="accordion-item-title">{ summaryLabel }</span>
			</summary>

			<div
				id={ controlID }
				className="accordion-panel"
				role="region"
				aria-labelledby={ triggerID }
				data-js-accordion-panel
			>
				<div className="accordion-panel__inner">
					<div className="accordion-content">
						<InnerBlocks.Content />
					</div>
				</div>
			</div>
		</details>
	);
}
