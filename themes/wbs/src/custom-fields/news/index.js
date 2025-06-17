import { registerPlugin } from '@wordpress/plugins';
import { CustomLinkFields } from '../components/CustomLinkFields';

registerPlugin( 'wbs-theme-meta-news-link-panel', {
	render: () => CustomLinkFields( 'wbs_external_link' ),
	icon: 'admin-links',
} );
