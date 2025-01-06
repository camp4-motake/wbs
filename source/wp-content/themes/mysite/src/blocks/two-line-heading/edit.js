import {
    AlignmentToolbar,
    BlockControls,
    HeadingLevelDropdown,
    RichText,
    useBlockProps,
} from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import './editor.css';

export default function Edit( { attributes, setAttributes } ) {
	const Tag = 'h' + attributes.level;
	const blockProps = useBlockProps( {
		className: `has-text-${ attributes.alignment }`,
	} );

	return (
		<>
			<BlockControls>
				<HeadingLevelDropdown
					value={ attributes.level }
					onChange={ ( val ) => setAttributes( { level: val } ) }
				/>
				<AlignmentToolbar
					value={ attributes.alignment }
					onChange={ ( val ) => setAttributes( { alignment: val } ) }
				/>
			</BlockControls>

			<Tag { ...blockProps }>
				<RichText
					className={ 'shoulder-content' }
					placeholder={ __( 'Shoulder Text', 'wbs' ) }
					tagName="small"
					value={ attributes.shoulderContent }
					onChange={ ( val ) =>
						setAttributes( { shoulderContent: val } )
					}
				/>
				<RichText
					className={ 'label-content' }
					placeholder={ __( '見出しテキスト', 'wbs' ) }
					tagName="span"
					value={ attributes.content }
					onChange={ ( val ) => setAttributes( { content: val } ) }
				/>
			</Tag>
		</>
	);
}
