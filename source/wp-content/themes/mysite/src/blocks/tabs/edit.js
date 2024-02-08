import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { select } from '@wordpress/data';
import { useEffect } from '@wordpress/element';
import { __ } from '@wordpress/i18n';

import './editor.css';

const ALLOWED_BLOCKS = ['wbs/tab'];

export default function Edit({ attributes, setAttributes, clientId }) {
	const { tabLabelsArray, updateChild /* , sideTabLayout */ } = attributes;
	const innerBlocks = select('core/block-editor').getBlocks(clientId);
	const labelsArray = innerBlocks.map((block) => block.attributes.tabLabel);
	const isLabelLengthUpdated = labelsArray.length !== tabLabelsArray.length;

	useEffect(() => {
		if (!(isLabelLengthUpdated || updateChild)) return;
		setAttributes({ tabLabelsArray: labelsArray });
		setAttributes({ updateChild: false });
	}, [
		attributes,
		isLabelLengthUpdated,
		labelsArray,
		setAttributes,
		updateChild,
	]);

	// const helpLabels = sideTabLayout ? __('Side tab layout selected', 'wbs') : __('Default layout', 'wbs');

	return (
		<div {...useBlockProps()}>
			<h2>{__('Tabbed Layout Block', 'wbs')}</h2>
			{/* <ToggleControl label={__('Switch to side tab layout', 'wbs')} help={helpLabels} checked={sideTabLayout} onChange={(toggle) => setAttributes({ sideTabLayout: toggle })} /> */}
			<InnerBlocks
				allowedBlocks={ALLOWED_BLOCKS}
				renderAppender={InnerBlocks.ButtonBlockAppender}
			/>
		</div>
	);
}
