import { InspectorControls, useBlockProps } from '@wordpress/block-editor';
import {
    BaseControl,
    PanelBody,
    RangeControl,
    SelectControl,
} from '@wordpress/components';
import { useSelect } from '@wordpress/data';
import { useEffect, useState } from '@wordpress/element';
import { __ } from '@wordpress/i18n';
import ServerSideRender from '@wordpress/server-side-render';
import { MAX_POSTS, MIN_POSTS, POST_TYPES, TAXONOMIES } from './constants';
import './editor.css';

export default function Edit( { name, attributes, setAttributes } ) {
	const { postTypeSlug, taxonomySlug, termID, postPerPage } = attributes;
	const blockProps = useBlockProps();
	const [ termSelect, setTermSelect ] = useState( null );

	const taxOptions = [ { label: __( '全ての記事', 'wbs' ), value: '' } ];

	/**
	 * get term list
	 * @see https://developer.wordpress.org/block-editor/reference-guides/data/data-core/#getentityrecords
	 */
	const terms = useSelect( ( select ) => {
		if ( ! taxonomySlug ) {
			return [];
		}
		return select( 'core' ).getEntityRecords( 'taxonomy', taxonomySlug, {
			per_page: -1,
			orderby: 'name',
			order: 'asc',
			_fields: 'id,name,slug',
		} );
	} );

	const onChangePostType = ( newPostTypeSlug ) => {
		setAttributes( { postTypeSlug: newPostTypeSlug } );
		setAttributes( { taxonomySlug: '' } );
		setAttributes( { termID: 0 } );
	};

	const onChangeTax = ( newTaxonomySlug ) => {
		setAttributes( { taxonomySlug: newTaxonomySlug } );
		setAttributes( { termID: 0 } );
	};

	const onChangeTerms = ( newTermID ) => {
		setAttributes( { termID: newTermID } );
	};

	/**
	 * update term select
	 */
	useEffect( () => {
		const termOptions = [
			{ label: __( '選択', 'wbs' ), value: 0, disabled: true },
		];
		const termsMap = terms?.map( ( t ) => ( {
			label: t.name,
			value: t.id,
		} ) );

		if ( ! taxonomySlug || ! termsMap ) {
			setTermSelect( termOptions );
			return;
		}

		setTermSelect( termOptions.concat( termsMap ) );
	}, [ taxonomySlug, terms ] );

	return (
		<>
			<InspectorControls>
				<PanelBody title={ __( '記事設定', 'wbs' ) }>
					<BaseControl
						id="postTypeSelect"
						label={ __( '投稿選択', 'wbs' ) }
						__nextHasNoMarginBottom
					>
						<SelectControl
							label={ __( '投稿タイプ', 'wbs' ) }
							value={ postTypeSlug }
							options={ POST_TYPES }
							onChange={ onChangePostType }
							__nextHasNoMarginBottom
						/>
						<SelectControl
							label={ __( 'タクソノミー', 'wbs' ) }
							value={ taxonomySlug }
							options={ taxOptions.concat(
								TAXONOMIES[ postTypeSlug ] || []
							) }
							onChange={ onChangeTax }
							__nextHasNoMarginBottom
						/>
						<SelectControl
							label={ __( 'カテゴリー', 'wbs' ) }
							value={ termID }
							options={ termSelect }
							onChange={ onChangeTerms }
							__nextHasNoMarginBottom
						/>
						<RangeControl
							label={ __( '最大表示数', 'wbs' ) }
							value={ postPerPage }
							onChange={ ( newPostPerPage ) =>
								setAttributes( { postPerPage: newPostPerPage } )
							}
							min={ MIN_POSTS }
							max={ MAX_POSTS }
							__nextHasNoMarginBottom
						/>
					</BaseControl>
				</PanelBody>
			</InspectorControls>

			<div { ...blockProps }>
				<ServerSideRender
					block={ name }
					attributes={ attributes }
					skipBlockSupportAttributes
				/>
			</div>
		</>
	);
}
