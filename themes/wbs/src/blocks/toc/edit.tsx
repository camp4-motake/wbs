import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import { PanelBody, RangeControl } from '@wordpress/components';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';

import type { BlockEditPropsWithName } from '../../shared/types';
import './editor.css';

export type TocAttributes = {
	minLevel: number;
	maxLevel: number;
};

export default function Edit( {
	name,
	attributes,
	setAttributes,
}: BlockEditPropsWithName< TocAttributes > ) {
	const { minLevel, maxLevel } = attributes;
	const blockProps = useBlockProps();

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( '見出しレベル', 'wbs' ) }>
					<RangeControl
						label={ __( '最小レベル', 'wbs' ) }
						help={ __( '目次に含める最小の見出しレベル', 'wbs' ) }
						value={ minLevel }
						onChange={ ( val ) =>
							setAttributes( {
								minLevel: val,
								maxLevel:
									val && val > maxLevel ? val : maxLevel,
							} )
						}
						min={ 2 }
						max={ 6 }
					/>
					<RangeControl
						label={ __( '最大レベル', 'wbs' ) }
						help={ __( '目次に含める最大の見出しレベル', 'wbs' ) }
						value={ maxLevel }
						onChange={ ( val ) =>
							setAttributes( {
								maxLevel: val,
								minLevel:
									val && val < minLevel ? val : minLevel,
							} )
						}
						min={ 2 }
						max={ 6 }
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<ServerSideRender
					block={ name }
					attributes={ attributes }
					skipBlockSupportAttributes
					EmptyResponsePlaceholder={ () => (
						<p className="toc__notice">
							{ __(
								'目次に表示できる見出しがありません。',
								'wbs'
							) }
						</p>
					) }
				/>
			</div>
		</>
	);
}
