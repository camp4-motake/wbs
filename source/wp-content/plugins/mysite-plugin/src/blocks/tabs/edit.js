import { __ } from '@wordpress/i18n';
import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { useSelect } from '@wordpress/data';
import { ToggleControl } from '@wordpress/components';
import './editor.css';
import './tab.js';

const ALLOWED_BLOCKS = ['wbs/tab'];

export default function Edit(props) {
	const { attributes, setAttributes } = props;
	const { tabLabelsArray, updateChild, sideTabLayout } = attributes;

	const buildTabLabelsArray = () => {
		//function gets child block attributes and saves as an array to parent attributes
		const parentBlockID = props.clientId;
		const { innerBlockCount } = useSelect((select) => ({
			innerBlockCount:
				select('core/block-editor').getBlockCount(parentBlockID),
		}));

		const tabLabels = [];

		for (let block = 0; block < innerBlockCount; block++) {
			const tabLabel = wp.data
				.select('core/block-editor')
				.getBlocks(parentBlockID)[block].attributes.tabLabel;
			tabLabels.push(tabLabel);
		}

		return tabLabels;
	};

	const labelsArray = buildTabLabelsArray();
	const labelLengthChange = labelsArray.length !== tabLabelsArray.length;

	if (labelLengthChange || updateChild) {
		setAttributes({ tabLabelsArray: labelsArray });
		setAttributes({ updateChild: false });
	}

	const onChangeTabLabel = (toggle) => {
		setAttributes({ sideTabLayout: toggle });
	};

	return (
		<div {...useBlockProps()}>
			<h2>Tabbed Layout Block</h2>
			<ToggleControl
				label="Switch to side tab layout"
				help={
					sideTabLayout
						? 'Side tab layout selected'
						: 'Defoult layout'
				}
				checked={sideTabLayout}
				onChange={onChangeTabLabel}
			/>
			<InnerBlocks
				allowedBlocks={ALLOWED_BLOCKS}
				renderAppender={InnerBlocks.ButtonBlockAppender}
			/>
		</div>
	);
}
