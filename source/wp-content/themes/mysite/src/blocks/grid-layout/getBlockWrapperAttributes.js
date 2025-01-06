import clsx from 'clsx';

export const getBlockWrapperAttributes = ( attributes ) => {
	return {
		className: clsx( 'grid-layout-block', {
			'has-child-subgrid': attributes.useSubgrid,
		} ),
		style: {
			display: 'grid',
			// gap: attributes.gap,
			rowGap: attributes.rowGap,
			columnGap: attributes.columnGap,
			...( attributes.gridType === 'columns'
				? {
						gridTemplateColumns: `repeat(${ attributes.columns }, 1fr)`,
				  }
				: {
						gridTemplateColumns: `repeat(auto-fit, minmax(${ attributes.minColumnWidth }, 1fr))`,
				  } ),
			...( attributes.columnSpan
				? {
						'--_grid-child-col-span': `${ attributes.columnSpan }`,
				  }
				: {} ),
			...( attributes.rowSpan
				? {
						'--_grid-child-row-span': `${ attributes.rowSpan }`,
				  }
				: {} ),
			...( attributes.minHeight
				? {
						'--_grid-child-min-block-size': `${ attributes.minHeight }`,
				  }
				: {} ),
			...( attributes.columnBorderRadius
				? {
						'--_grid-child-border-radius': `${ attributes.columnBorderRadius }`,
				  }
				: {} ),
		},
	};
};
