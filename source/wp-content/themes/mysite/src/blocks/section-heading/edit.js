import {
    BlockControls,
    HeadingLevelDropdown,
    RichText,
    useBlockProps,
} from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

import './editor.css';

export default function Edit( { attributes, setAttributes } ) {
	const { level, headContent, content } = attributes;
	const Tag = 'h' + level;
	const blockProps = useBlockProps();

	return (
		<>
			<BlockControls>
				<HeadingLevelDropdown
					value={ level }
					onChange={ ( val ) => setAttributes( { level: val } ) }
				/>
			</BlockControls>

			<div { ...blockProps }>
				<Tag className="head-content">
					<RichText
						placeholder={ __( 'English Text', 'wbs' ) }
						tagName="span"
						value={ headContent }
						allowedFormats={ [] }
						onChange={ ( val ) =>
							setAttributes( { headContent: val } )
						}
					/>
				</Tag>
				<RichText
					className="label-content"
					placeholder={ __( '日本語テキスト', 'wbs' ) }
					tagName="div"
					allowedFormats={ [] }
					value={ content }
					onChange={ ( val ) => setAttributes( { content: val } ) }
				/>
			</div>
		</>
	);
}
