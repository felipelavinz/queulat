<?php

namespace Queulat\Helpers;

class Arrays {
	private function __construct() {
	}

	/**
	 * Determines if an array is associative.
	 *
	 * An array is "associative" if it doesn't have sequential numeric keys beginning with zero.
	 *
	 * @param  iterable|object $array
	 * @return bool
	 */
	public static function is_associative($array) : bool {
		$keys = is_array( $array ) ? array_keys( $array ) : array_keys( $array->getArrayCopy() );
		return array_keys( $keys ) !== $keys;
	}

	public static function flatten( array $array, int $mode = \Minwork\Helper\Arr::UNPACK_ALL ) : array {
		return \Minwork\Helper\Arr::unpack( $array, $mode );
	}

	/**
	 * Reverse a flattened array in its original form.
	 *
	 * @param  array  $array flattened array
	 * @param  string $glue  glue used in flattening
	 * @return array  the unflattened array
	 */
	public static function reverse_flatten(array $array, string $glue = '.') : array {
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

	/**
	 * Recursively filter an array
	 *
	 * @param  array    $array    The input array
	 * @param  callable $callback A custom function for filtering (by default, uses array_filter)
	 * @return array              Filtered array
	 */
	public static function filter_recursive(array $array, $callback = null) {
		foreach ( $array as &$value ) {
			if ( is_array( $value ) ) {
				$value = $callback === null ? static::filter_recursive( $value ) : static::filter_recursive( $value, $callback );
			}
		}
		return $callback === null ? array_filter( $array ) : array_filter( $array, $callback );
	}
}