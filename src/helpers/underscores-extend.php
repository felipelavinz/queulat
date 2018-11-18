<?php

use Underscore\Types\Arrays;
use Underscore\Types\Strings;

/**
 * Determines if an array is associative.
 *
 * An array is "associative" if it doesn't have sequential numeric keys beginning with zero.
 *
 * @param  iterable $array
 * @return bool
 * @internal Copied from Laravel framework (from Taylor Otwell under MIT License)
 * @link https://github.com/laravel/framework/blob/be7fbb60376bd61f07e9c637473e5b2cf7eebe5c/src/Illuminate/Support/Arr.php#L279-L292
 */
Arrays::extend(
	'isAssociative', function( iterable $array ) : bool {
		$keys = is_array( $array ) ? array_keys( $array ) : array_keys( $array->getArrayCopy() );
		return array_keys( $keys ) !== $keys;
	}
);

/**
 * Reverse a flattened array in its original form.
 *
 * @param  array  $array flattened array
 * @param  string $glue  glue used in flattening
 * @return array  the unflattened array
 * @internal (Mostly) copied from Fuel framework (from the FuelPHP Development Team under MIT License)
 * @link https://github.com/fuel/core/blob/6c48d4e63bea3c268c97f0cc085a15ef57d40032/classes/arr.php#L382-L422
 */
Arrays::extend(
	'reverseFlatten', function( array $array, string $glue = '.' ) : array {
		$return = array();
		foreach ( $array as $key => $value ) {
			if ( stripos( $key, $glue ) !== false ) {
				$keys = explode( $glue, $key );
				$temp =& $return;
				while ( count( $keys ) > 1 ) {
					$key = array_shift( $keys );
					$key = is_numeric( $key ) ? (int) $key : $key;
					if ( ! isset( $temp[ $key ] ) or ! is_array( $temp[ $key ] ) ) {
						$temp[ $key ] = array();
					}
					$temp =& $temp[ $key ];
				}
				$key          = array_shift( $keys );
				$key          = is_numeric( $key ) ? (int) $key : $key;
				$temp[ $key ] = $value;
			} else {
				$key            = is_numeric( $key ) ? (int) $key : $key;
				$return[ $key ] = $value;
			}
		}
		return $return;
	}
);

/**
 * Recursively filter an array
 *
 * @param  array    $array    The input array
 * @param  callable $callback A custom function for filtering (by default, uses array_filter)
 * @return array              Filtered array
 */
Arrays::extend(
	'filterRecursive', function( array $array, $callback = null ) {
		foreach ( $array as &$value ) {
			if ( is_array( $value ) ) {
				$value = $callback === null ? Arrays::filterRecursive( $value ) : Arrays::filterRecursive( $value, $callback );
			}
		}
		return $callback === null ? array_filter( $array ) : array_filter( $array, $callback );
	}
);

/**
 * Convert a string to "kebab-case" (lowercase and separated by dashes)
 *
 * @param  string $string The input string
 * @param  int    $limit  Limit the conversion to an amount of parts
 * @return string         String converted to kebab-case
 */
Strings::extend(
	'toKebabCase', function( string $string, int $limit = 0 ) : string {
		$string = Strings::toSnakeCase( $string, $limit );
		return str_replace( '_', '-', $string );
	}
);

/**
 * Convert a string to Capitalized_Snake_Case
 *
 * @param  string $string The input string
 * @param  int    $limit  Limit the conversion to this amount of parts
 * @return string         String converted to Capitalized_Snake_Case
 */
Strings::extend(
	'toCapitalizedSnakeCase', function( string $string, int $limit = 0 ) : string {
		$string = Strings::toSnakeCase( $string, $limit );
		$string = Strings::explode( $string, '_' );
		$string = array_map( 'ucfirst', $string );
		return implode( '_', $string );
	}
);

/**
 * Limit a string up to the desired amount of characters, but always finish
 * on full words (and optionally, the $end string)
 *
 * @param  string $string The string that will be cut
 * @param  int    $limit  The maximum amount of characters for the string
 * @param  string $end    What to append at the end of the string, if the initial length it's over $limit
 * @return string         The shortened string
 */
Strings::extend(
	'limitWords', function( string $string, int $limit, string $end = '' ) : string {
		// cleanup the string
		$string = function_exists( 'wp_strip_all_tags' ) ? wp_strip_all_tags( $string ) : strip_tags( $string );

		if ( function_exists( 'mb_strlen' ) && mb_strlen( $string ) < $limit ) {
			return $string;
		} elseif ( strlen( $string ) < $limit ) {
			return $string;
		}

		$string = substr( $string, 0, $limit );
		$words  = explode( ' ', $string );
		// pop a possibly-cut word
		array_pop( $words );
		// pop the latest of the words, get it clean if it ends on a punctuation mark
		$last_word = array_pop( $words );
		$last_word = preg_replace( '/[\W]/u', '', $last_word );
		$words[]   = $last_word;
		return implode( ' ', $words ) . $end;
	}
);
