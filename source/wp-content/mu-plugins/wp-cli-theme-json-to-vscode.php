<?php

/**
 * Convert CSS variables from theme.json stylesheet to VSCode custom data format
 */

if (!defined('WP_CLI') || !WP_CLI) {
	return;
}

class Theme_JSON_CSS_Vars_Command
{
	/**
	 * Extract CSS variables from theme.json stylesheet and output in VSCode format
	 *
	 * ## OPTIONS
	 *
	 * [--format=<format>]
	 * : The output format
	 * ---
	 * default: json
	 * options:
	 *   - json
	 *   - jsonc
	 *   - css
	 * ---
	 *
	 * [--output=<path>]
	 * : The output file path. If not specified, outputs to STDOUT.
	 *
	 * [--debug]
	 * : Enable debug output
	 *
	 * ## EXAMPLES
	 *
	 *     # Output VSCode settings as JSON
	 *     $ wp css-vars-to-vscode
	 *
	 *     # Output raw CSS stylesheet
	 *     $ wp css-vars-to-vscode --format=css
	 *
	 *     # Output to file with debug info
	 *     $ wp css-vars-to-vscode --output=./vscode/css-vars.json --debug
	 */
	public function __invoke($args, $assoc_args)
	{
		try {
			$format = $assoc_args['format'] ?? 'json';
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

			// If CSS format is requested, output stylesheet directly
			if ($format === 'css') {
				$content = '/** DO NOT EDIT: theme.json CSS styles to autocomplete css variables in vscode */' . "\n" . $stylesheet;
			} else {
				// Extract CSS variables using regex
				$variables = $this->extract_css_vars_from_stylesheet($stylesheet);

				if ($debug) {
					WP_CLI::line("Debug: Extracted variables:");
					WP_CLI::line(json_encode($variables, JSON_PRETTY_PRINT));
				}

				// Format for VSCode
				$vscode_format = [
					"version" => 1.1,
					"properties" => array_map(function ($var) {
						return [
							"name" => $var['name'],
							"description" => $var['value']
						];
					}, $variables),
					"triggerCharacters" => ["--", "-w", "-p"],
					"suggestions" => array_map(function ($var) {
						return [
							"label" => $var['name'],
							"kind" => "value",
							"insertText" => $var['name'],
							"documentation" => $var['value']
						];
					}, $variables)
				];

				// Add comments if JSONC format is requested
				if ($format === 'jsonc') {
					$vscode_format = array_merge(
						["// CSS custom properties from WordPress theme.json stylesheet"],
						$vscode_format
					);
				}

				$content = json_encode($vscode_format, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
			}

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
					"Generated %s at: %s",
					$format === 'css' ? 'stylesheet' : 'CSS custom properties',
					$output_path
				));
			} else {
				WP_CLI::line($content);
			}
		} catch (Exception $e) {
			WP_CLI::error("An error occurred: " . $e->getMessage());
		}
	}

	/**
	 * Extract CSS variables and their values from stylesheet
	 */
	private function extract_css_vars_from_stylesheet($stylesheet)
	{
		$variables = [];
		$pattern = '/(?:^|\s|;)(--[a-zA-Z0-9-]+)\s*:\s*([^;]+);/m';

		if (preg_match_all($pattern, $stylesheet, $matches, PREG_SET_ORDER)) {
			foreach ($matches as $match) {
				$name = trim($match[1]);
				$value = trim($match[2]);

				// Skip duplicates, keeping the last occurrence
				$variables[$name] = [
					'name' => $name,
					'value' => $value
				];
			}
		}

		return array_values($variables);
	}
}

if (class_exists('WP_CLI')) {
	WP_CLI::add_command('css-vars-to-vscode', 'Theme_JSON_CSS_Vars_Command');
}
