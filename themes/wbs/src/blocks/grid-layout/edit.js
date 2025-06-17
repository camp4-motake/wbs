import {
	InnerBlocks,
	InspectorControls,
	useBlockProps,
} from '@wordpress/block-editor';
import { createBlock } from '@wordpress/blocks';
import {
	Button,
	PanelBody,
	SelectControl,
	TextControl,
	ToggleControl,
	// WORKAROUND
	// eslint-disable-next-line @wordpress/no-unsafe-wp-apis
	__experimentalUnitControl as UnitControl,
} from '@wordpress/components';
import { select, useDispatch } from '@wordpress/data';
import { __ } from '@wordpress/i18n';
import './editor.css';
import { getBlockWrapperAttributes } from './getBlockWrapperAttributes';

const ALLOWED_BLOCKS = [ 'core/column', 'wbs/grid-layout-column' ];

export default function Edit( { attributes, setAttributes, clientId } ) {
	const wrapperAttributes = getBlockWrapperAttributes( attributes );
	const blockProps = useBlockProps( { ...wrapperAttributes } );

	// カスタムアペンダーを作成
	const CustomBlockAppender = () => {
		const { replaceInnerBlocks } = useDispatch( 'core/block-editor' );
		const handleDuplicateLastBlock = ( event ) => {
			event.stopPropagation();

			// 再帰的にブロックをディープコピーする関数を先に定義
			const duplicateBlocks = ( blocks ) => {
				return blocks.map( ( block ) =>
					createBlock(
						block.name,
						{ ...block.attributes },
						block.innerBlocks
							? duplicateBlocks( block.innerBlocks )
							: []
					)
				);
			};

			// 現在の InnerBlocks を取得
			const innerBlocks =
				select( 'core/block-editor' ).getBlocksByClientId(
					clientId
				)[ 0 ].innerBlocks;

			if ( innerBlocks.length > 0 ) {
				// 最後のブロックを取得
				const lastBlock = innerBlocks[ innerBlocks.length - 1 ];

				// ディープコピーされた内部ブロックを使用して新しいブロックを作成
				const duplicatedBlock = createBlock(
					lastBlock.name,
					{ ...lastBlock.attributes },
					lastBlock.innerBlocks
						? duplicateBlocks( lastBlock.innerBlocks )
						: []
				);

				// 新しいブロックを追加
				replaceInnerBlocks(
					clientId,
					[ ...innerBlocks, duplicatedBlock ],
					false
				);
			}
		};

		return (
			<div className="custom-ui-block-appender">
				{ select( 'core/block-editor' ).getBlocksByClientId(
					clientId
				)[ 0 ].innerBlocks.length > 0 && (
					<Button
						onClick={ handleDuplicateLastBlock }
						variant="secondary"
					>
						{ __( '最後のブロックを複製' ) }
					</Button>
				) }
				<InnerBlocks.ButtonBlockAppender />
			</div>
		);
	};

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( 'グリッド設定' ) }>
					<SelectControl
						label={ __( 'グリッドタイプ' ) }
						value={ attributes.gridType }
						options={ [
							{ label: __( '固定カラム' ), value: 'columns' },
							{
								label: __( 'レスポンシブ' ),
								value: 'responsive',
							},
							{
								label: __( 'レスポンシブ（幅広時は固定）' ),
								value: 'complex',
							},
						] }
						onChange={ ( value ) =>
							setAttributes( { gridType: value } )
						}
						__nextHasNoMarginBottom
					/>
					{ [ 'columns', 'complex' ].includes(
						attributes.gridType
					) && (
						<TextControl
							type="number"
							label={ __( 'カラム数' ) }
							value={ attributes.columns }
							onChange={ ( value ) =>
								setAttributes( { columns: parseInt( value ) } )
							}
							min={ 1 }
							max={ 12 }
							__nextHasNoMarginBottom
						/>
					) }
					{ [ 'responsive', 'complex' ].includes(
						attributes.gridType
					) && (
						<UnitControl
							label={ __( '最小カラム幅' ) }
							value={ attributes.minColumnWidth }
							onChange={ ( value ) =>
								setAttributes( { minColumnWidth: value } )
							}
							units={ [
								{ value: 'px', label: 'px' },
								{ value: 'rem', label: 'rem' },
								{ value: 'em', label: 'em' },
								{ value: '%', label: '%' },
							] }
						/>
					) }
					<UnitControl
						label={ __( 'グリッド間隔（横方向）' ) }
						value={ attributes.columnGap }
						onChange={ ( value ) =>
							setAttributes( { columnGap: value } )
						}
						units={ [
							{ value: 'px', label: 'px' },
							{ value: 'rem', label: 'rem' },
							{ value: 'em', label: 'em' },
							{ value: 'cqi', label: 'cqi' },
							{ value: 'cqb', label: 'cqb' },
						] }
					/>
					<UnitControl
						label={ __( 'グリッド間隔（縦方向）' ) }
						value={ attributes.rowGap }
						onChange={ ( value ) =>
							setAttributes( { rowGap: value } )
						}
						units={ [
							{ value: 'px', label: 'px' },
							{ value: 'rem', label: 'rem' },
							{ value: 'em', label: 'em' },
							{ value: 'cqi', label: 'cqi' },
							{ value: 'cqb', label: 'cqb' },
						] }
					/>
				</PanelBody>

				<PanelBody title={ __( 'カラム設定' ) }>
					<ToggleControl
						label={ __( 'Subgrid を使用' ) }
						help={ __( 'subgrid で入れ子のブロックを整列' ) }
						checked={ attributes.useSubgrid }
						onChange={ ( value ) =>
							setAttributes( { useSubgrid: value } )
						}
						__nextHasNoMarginBottom
					/>
					{ attributes.useSubgrid && (
						<>
							<TextControl
								type="number"
								label={ __( 'Col Span' ) }
								value={ attributes.columnSpan }
								onChange={ ( value ) =>
									setAttributes( {
										columnSpan: parseInt( value || 1 ),
									} )
								}
								min={ 1 }
								max={ 12 }
								__nextHasNoMarginBottom
							/>
							<TextControl
								type="number"
								label={ __( 'Row Span' ) }
								help={ __(
									'配置を揃えるにはカラムブロック内に配置するブロックと同じ数にします'
								) }
								value={ attributes.rowSpan }
								onChange={ ( value ) =>
									setAttributes( {
										rowSpan: parseInt( value || 1 ),
									} )
								}
								min={ 1 }
								max={ 12 }
								__nextHasNoMarginBottom
							/>
						</>
					) }
					<UnitControl
						label={ __( '最小高さ' ) }
						value={ attributes.minHeight }
						onChange={ ( value ) =>
							setAttributes( { minHeight: value } )
						}
						units={ [
							{ value: 'px', label: 'px' },
							{ value: 'rem', label: 'rem' },
							{ value: 'em', label: 'em' },
							{ value: 'vh', label: 'vh' },
							{ value: 'auto', label: 'auto' },
						] }
					/>
					<UnitControl
						label={ __( '角丸' ) }
						value={ attributes.columnBorderRadius }
						onChange={ ( value ) =>
							setAttributes( { columnBorderRadius: value } )
						}
						units={ [
							{ value: 'px', label: 'px' },
							{ value: 'rem', label: 'rem' },
							{ value: 'em', label: 'em' },
							{ value: 'vi', label: 'vi' },
							{ value: 'auto', label: 'auto' },
						] }
					/>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<InnerBlocks
					allowedBlocks={ ALLOWED_BLOCKS }
					renderAppender={ CustomBlockAppender }
				/>
			</div>
		</>
	);
}
