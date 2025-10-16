<?php

/**
 * Convert CSS variables from theme.json stylesheet to CSS format
 */

if (!defined('WP_CLI') || !WP_CLI) {
	return;
}

class WBS_CLI_Export_Theme_JSON_Styles_Command
{
	/**
	 * Extract CSS variables from theme.json stylesheet and output as CSS
	 *
	 * ## OPTIONS
	 *
	 * [--output=<path>]
	 * : The output file path. If not specified, outputs to STDOUT.
	 *
	 * [--debug]
	 * : Enable debug output
	 *
	 * ## EXAMPLES
	 *
	 *     # Output CSS stylesheet to STDOUT
	 *     $ wp export-theme-json-styles
	 *
	 *     # Output to file with debug info
	 *     $ wp export-theme-json-styles --output=./styles/theme-vars.css --debug
	 */
	public function __invoke($args, $assoc_args)
	{
		try {
			$output_path = $assoc_args['output'] ?? null;
			$debug = isset($assoc_args['debug']);

			if ($debug) {
				WP_CLI::line("Debug: Getting theme.json stylesheet...");
			}

			// Get theme.json stylesheet
			$theme_json = WP_Theme_JSON_Resolver::get_merged_data();
			$stylesheet = $theme_json->get_stylesheet();

			if ($debug) {
				WP_CLI::line("Debug: Stylesheet content:");
				WP_CLI::line($stylesheet);
			}

			$content = '/** DO NOT EDIT: exported from theme.json css styles for autocompletion of css variables in editors such as vscode */' . "\n" . $stylesheet;


			if ($output_path) {
				$dir = dirname($output_path);
				if (!file_exists($dir)) {
					if (!mkdir($dir, 0755, true)) {
						WP_CLI::error("Failed to create directory: {$dir}");
						return;
					}
				}

				if (file_put_contents($output_path, $content) === false) {
					WP_CLI::error("Failed to write to file: {$output_path}");
					return;
				}

				WP_CLI::success(sprintf(
					"Generated CSS stylesheet at: %s",
					$output_path
				));
			} else {
				WP_CLI::line($content);
			}
		} catch (Exception $e) {
			WP_CLI::error("An error occurred: " . $e->getMessage());
		}
	}
}

if (class_exists('WP_CLI')) {
	WP_CLI::add_command('export-theme-json-styles', 'WBS_CLI_Export_Theme_JSON_Styles_Command');
}
