/* eslint-disable no-console */
import useBlockAttributesObserver from './useBlockAttributesObserver';

/**
 * Debug Helper: Block Update watcher
 */
export const useDevBlockWatcher = () => {
	useBlockAttributesObserver( ( changes ) => {
		changes.forEach( ( change ) => {
			switch ( change.type ) {
				case 'UPDATE':
					console.log(
						`Block "${ change.blockName }" (${ change.blockId }): ` +
							`属性 "${ change.attributeName }" が ` +
							`"${ change.oldValue }" から "${ change.newValue }" に変更されました`
					);
					break;
				case 'ADD':
					console.log( `新しいブロック追加: ${ change.blockName }` );
					break;
				case 'REMOVE':
					console.log( `ブロック削除: ${ change.blockName }` );
					break;
			}
		} );
	} );

	return null;
};
