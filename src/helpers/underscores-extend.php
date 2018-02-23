<?php

use Underscore\Types\Arrays;
use Underscore\Types\Strings;

/**
 * Determines if an array is associative.
 *
 * An array is "associative" if it doesn't have sequential numeric keys beginning with zero.
 *
 * @param  array   $array
 * @return boolean
 * @internal Copied from Laravel framework (from Taylor Otwell under MIT License)
 * @link https://github.com/laravel/framework/blob/be7fbb60376bd61f07e9c637473e5b2cf7eebe5c/src/Illuminate/Support/Arr.php#L279-L292
 */
Arrays::extend(
	'isAssociative', function( $array ) : bool {
		$keys = array_keys( $array );
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

Strings::extend(
	'toKebabCase', function( string $string, $limit = 0 ) : string {
		$string = Strings::toSnakeCase( $string, $limit );
		return str_replace( '_', '-', $string );
	}
);

Strings::extend(
	'toCapitalizedSnakeCase', function( string $string, $limit = 0 ) : string {
		$string = Strings::toSnakeCase( $string, $limit );
		$string = Strings::explode( $string, '_' );
		$string = array_map('ucfirst', $string);
		return implode('_', $string);
	}
);