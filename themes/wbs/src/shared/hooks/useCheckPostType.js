import { useSelect } from '@wordpress/data';

export const useCheckPostType = ( postTypes = [] ) => {
	const { postType } = useSelect( ( select ) => {
		const coreEditor = select( 'core/editor' );
		return { postType: coreEditor.getCurrentPostType() };
	}, [] );

	return [ postTypes?.includes( postType ) ];
};
