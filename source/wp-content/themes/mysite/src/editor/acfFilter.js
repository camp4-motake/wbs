export const acfFilter = () => {
	if (!window?.acf) return;

	/**
	 * acf color_picker_args
	 * https://www.advancedcustomfields.com/resources/adding-custom-javascript-fields/#colorpickerargs
	 */
	window.acf.add_filter('color_picker_args', function (args /* , $field */) {
		// カラーピッカーのスウォッチをカスタムカラーに変更
		args.palettes = [
			'#5CB582',
			'#7174B4',
			'#D69359',
			'#798184',
			'#468BB7',
			'#883623',
			'#0E5F93',
		];
		return args;
	});
};
