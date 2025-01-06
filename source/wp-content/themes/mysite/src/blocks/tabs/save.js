import { InnerBlocks, useBlockProps } from '@wordpress/block-editor';
import { RawHTML } from '@wordpress/element';
import './style.css';

export default function save( { attributes } ) {
	const { tabLabelsArray, sideTabLayout } = attributes;
	const blockProps = sideTabLayout
		? useBlockProps.save( { className: 'alignFull side-tab-layout' } )
		: useBlockProps.save( { className: 'alignFull ' } );

	return (
		<div { ...blockProps }>
			<div
				className="tab-labels entry-container"
				role="tablist"
				aria-label="tabbed content"
			>
				{ tabLabelsArray.map( ( label, i ) => {
					const isFirst = i === 0;
					return (
						<button
							key={ 'tab-' + i }
							className={ isFirst ? 'tab-label' : 'tab-label' }
							role="tab"
							aria-selected={ isFirst ? 'true' : 'false' }
							aria-controls={ label }
							tabIndex="0"
							{ ...{
								'@click': `activeTab = ${ i }`,
								':aria-selected': `activeTab === ${ i }`,
							} }
						>
							<RawHTML>{ label }</RawHTML>
						</button>
					);
				} ) }
			</div>
			<div className="tab-content">
				<InnerBlocks.Content />
			</div>
		</div>
	);
}
