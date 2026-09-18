declare module '*.css';

declare module '@wordpress/server-side-render' {
	import type { ComponentType } from 'react';

	export interface ServerSideRenderProps {
		block: string;
		attributes?: Record< string, unknown >;
		className?: string;
		httpMethod?: 'GET' | 'POST';
		urlQueryArgs?: Record< string, string | number | boolean >;
		skipBlockSupportAttributes?: boolean;
		EmptyResponsePlaceholder?: ComponentType;
		ErrorResponsePlaceholder?: ComponentType;
		LoadingResponsePlaceholder?: ComponentType;
	}

	const ServerSideRender: ComponentType< ServerSideRenderProps >;
	export default ServerSideRender;
}

type WPGlobal = {
	blocks?: typeof import('@wordpress/blocks');
};

declare const wp: WPGlobal;

interface Window {
	wp?: WPGlobal;
	acf?: {
		add_filter: (
			name: string,
			callback: (
				args: { palettes?: string[] } & Record< string, unknown >
			) => unknown
		) => void;
	};
}
