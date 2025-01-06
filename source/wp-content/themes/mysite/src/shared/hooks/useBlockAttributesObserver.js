import { select, subscribe } from '@wordpress/data';
import { useEffect, useRef } from '@wordpress/element';

const useBlockAttributesObserver = ( callback ) => {
	const prevBlocksRef = useRef( {} );

	useEffect( () => {
		// ブロックの属性を取得する関数
		const getBlocksAttributes = () => {
			const blocks = select( 'core/block-editor' ).getBlocks();
			return blocks.reduce( ( acc, block ) => {
				acc[ block.clientId ] = {
					name: block.name,
					attributes: { ...block.attributes },
				};
				return acc;
			}, {} );
		};

		// 変更された属性を検出する関数
		const detectChanges = ( prevBlocks, currentBlocks ) => {
			const changes = [];

			Object.keys( currentBlocks ).forEach( ( blockId ) => {
				const currentBlock = currentBlocks[ blockId ];
				const prevBlock = prevBlocks[ blockId ];

				if ( ! prevBlock ) {
					// 新しいブロックが追加された場合
					changes.push( {
						type: 'ADD',
						blockId,
						blockName: currentBlock.name,
						attributes: currentBlock.attributes,
					} );
					return;
				}

				// 属性の変更を検出
				Object.keys( currentBlock.attributes ).forEach(
					( attrName ) => {
						const prevValue = prevBlock.attributes[ attrName ];
						const currentValue =
							currentBlock.attributes[ attrName ];

						if (
							JSON.stringify( prevValue ) !==
							JSON.stringify( currentValue )
						) {
							changes.push( {
								type: 'UPDATE',
								blockId,
								blockName: currentBlock.name,
								attributeName: attrName,
								oldValue: prevValue,
								newValue: currentValue,
							} );
						}
					}
				);
			} );

			// 削除されたブロックを検出
			Object.keys( prevBlocks ).forEach( ( blockId ) => {
				if ( ! currentBlocks[ blockId ] ) {
					changes.push( {
						type: 'REMOVE',
						blockId,
						blockName: prevBlocks[ blockId ].name,
					} );
				}
			} );

			return changes;
		};

		// ストアの変更を購読
		const unsubscribe = subscribe( () => {
			const currentBlocks = getBlocksAttributes();
			const changes = detectChanges(
				prevBlocksRef.current,
				currentBlocks
			);

			if ( changes.length > 0 ) {
				callback( changes );
			}

			prevBlocksRef.current = currentBlocks;
		} );

		// クリーンアップ関数
		return () => unsubscribe();
	}, [ callback ] );
};

export default useBlockAttributesObserver;
