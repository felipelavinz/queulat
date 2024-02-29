<?php

namespace Queulat\Helpers;

use Doctrine\Inflector\InflectorFactory;

class Strings {
	private function __construct() {
	}

	/**
	 * Convert a string to "kebab-case" (lowercase and separated by dashes)
	 *
	 * @param  string $string The input string.
	 * @param  int    $limit  Limit the conversion to an amount of parts.
	 * @return string         String converted to kebab-case
	 */
	public static function to_kebab_case(string $string, int $limit = 0) : string {
		$string = static::to_snake_case($string, $limit);
		return str_replace('_', '-', $string);
	}

	/**
	 * Convert a string to "snake_case" (lowercase and separated by underscores)
	 * @param string $string The input string.
	 * @return string String converted to snake_case
	 */
	public static function to_snake_case(string $string) : string {
        return preg_replace_callback('/([A-Z])/', function ($match) {
            return '_'.strtolower($match[1]);
        }, $string);
	}

	/**
	 * Convert a string to Capitalized_Snake_Case
	 *
	 * @param  string $string The input string.
	 * @param  int    $limit  Limit the conversion to this amount of parts.
	 * @return string         String converted to Capitalized_Snake_Case
	 */
	public static function to_capitalized_snake_case(string $string, int $limit = 0) : string {
		$string = preg_replace('/[^a-zA-Z0-9]/', '-', $string);
		$string = explode($string, '-');
		$string = array_map(
			function($item) {
				return strtoupper($item[0]) . substr($item, 1);
			},
			$string
		);
		return implode('_', $string);
	}

	/**
	 * Limit a string up to the desired amount of characters, but always finish
	 * on full words (and optionally, the $end string)
	 *
	 * @param  string $string The string that will be cut.
	 * @param  int    $limit  The maximum amount of characters for the string.
	 * @param  string $end    What to append at the end of the string, if the initial length it's over $limit.
	 * @return string         The shortened string
	 */
	public static function limit_words(string $string, int $limit, string $end = '') : string {
		// cleanup the string.
		$string = function_exists('wp_strip_all_tags') ? wp_strip_all_tags($string) : strip_tags($string);

		if (function_exists('mb_strlen') && mb_strlen($string) < $limit) {
			return $string;
		} elseif (strlen($string) < $limit) {
			return $string;
		}

		$string = substr($string, 0, $limit);
		$words  = explode(' ', $string);
		// pop a possibly-cut word.
		array_pop($words);
		// pop the latest of the words, get it clean if it ends on a punctuation mark.
		$last_word = array_pop($words);
		$last_word = preg_replace('/[\W]/u', '', $last_word);
		$words[]   = $last_word;
		return implode(' ', $words) . $end;
	}

	/**
	 * Get the plural form of an English word
	 *
	 * @param string $input Input string.
	 * @return string Pluralized string
	 */
	public static function plural( string $input ) : string {
		$inflector = InflectorFactory::create()->build();
		return $inflector->pluralize( $input );
	}
}