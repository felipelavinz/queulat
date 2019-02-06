<?php
/**
 * @package Queulat
 */
use Underscore\Types\Arrays;

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
		foreach ( $rules as $pattern => $callbacks ) {
			if ( preg_match( '/'. $pattern .'/', $key ) ) {
				foreach ( $callbacks as $callback ) {
					$sanitized[ $key ] = $callback( $val );
				}
			}
		}
	}
	return Arrays::reverseFlatten( $sanitized );
}