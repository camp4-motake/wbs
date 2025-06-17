import clsx from 'clsx';

export const getBlockWrapperAttributes = ( attributes ) => {
	return {
		className: clsx( 'grid-layout-block', {
			'has-grid-type-columns': attributes.gridType === 'columns',
			'has-grid-type-responsive': attributes.gridType === 'responsive',
			'has-grid-type-complex': attributes.gridType === 'complex',
			'has-child-subgrid': attributes.useSubgrid,
		} ),
		style: {
			// gap: attributes.gap,
			// rowGap: attributes.rowGap,
			// columnGap: attributes.columnGap,
			// ...( attributes.gridType === 'columns' ? { '--_grid_template_columns': `repeat(${ attributes.columns }, 1fr)`, } : { '--_grid_template_columns': `repeat(auto-fit, minmax(${ attributes.minColumnWidth }, 1fr))`, } ),

			display: 'grid',
			'--_row-gap': `${ attributes.rowGap }`,
			'--_col-gap': `${ attributes.columnGap }`,
			'--_grid-columns': `${ attributes.columns }`,
			'--_grid-min-column-width': attributes.minColumnWidth,
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
