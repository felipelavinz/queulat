<?php
/**
 * @package Queulat
 */

use Queulat\Helpers\Arrays;

/**
 * Get the URL to a given file from Queulat.
 *
 * @param string $path The desired path
 * @return string
 */
function queulat_url( string $path = '' ) : string {
	return plugins_url( $path, dirname( dirname( __FILE__ ) ) );
}

/**
 * Apply sanitization functions on the given data
 *
 * @param array $data  Input data.
 * @param array $rules Sanitization rules. Keys are regular expressions to match against the input array keys, values are an array of callback functions.
 * @return array Sanitized data.
 */
function queulat_sanitizer( array $data, array $rules ) : array {
	$input     = Arrays::flatten( $data );
	$sanitized = [];
	foreach ( $input as $key => $val ) {
		foreach ( $rules as $rule => $callbacks ) {
			$pattern = str_replace( '\*', '[^\.]*', preg_quote( $rule ) );
			$matches = preg_match( '/^' . $pattern . '$/', $key );
			if ( (bool) $matches ) {
				foreach ( $callbacks as $callback ) {
					$sanitized[ $key ] = $callback( $val );
				}
			}
		}
	}
	return Arrays::reverse_flatten( $sanitized );
}
