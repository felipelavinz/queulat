<?php

namespace Queulat\Generator;

class Renderer {

	/**
	 * Get the length of the longest array key
	 *
	 * @param array $keys_arr An array of keys
	 * @return integer        The length of the longest key
	 */
	public static function get_longest_key_length( array $keys_arr ) : int {
		$longest_key = 0;
		foreach ( $keys_arr as $key ) {
			if ( strlen( $key) > $longest_key ) {
				$longest_key = strlen( $key );
			}
		}
		return $longest_key;
	}

	/**
	 * Render an array member as key => val
	 *
	 * @param string|int $key           The member key (variable name)
	 * @param mixed $val                Member value
	 * @param integer $pad_key_to       Number of spaces that keys will be padded to
	 * @param boolean $localize_strings Whether to localize strings
	 * @param string $textdomain        The localization textdomain to use
	 * @return string                   A single line of the array output
	 */
	public static function render_array_member( $key, $val, int $pad_key_to = 0, bool $localize_strings = true, string $textdomain = '' ) : string {
		$padding = $pad_key_to - strlen( $key ) > 0 ? str_repeat( ' ', $pad_key_to - strlen( $key ) ) : '';
		$key     = is_int( $key ) ? $key: "'$key'";
		if ( is_string( $val ) ) {
			$val = $localize_strings && $textdomain ? "__('{$val}', '$textdomain')" : "'{$val}'";
			return "{$key} {$padding}=> {$val},\n";
		} elseif ( is_int( $val ) || is_float( $val ) ) {
			return "{$key} {$padding}=> {$val},\n";
		} elseif ( is_bool( $val ) ) {
			$prop_val = $val ? 'true' : 'false';
			return "{$key} {$padding}=> {$prop_val},\n";
		} elseif ( is_null( $val ) ) {
			return "{$key} {$padding}=> null,\n";
		} elseif ( is_array( $val ) ) {
			if ( empty( $val ) ) {
				return "{$key} {$padding}=> [],\n";
			}
			$buffer = "{$key} {$padding}=> [\n";
			$longest_key = static::get_longest_key_length( array_keys( $val ) );
			foreach ( $val as $val_key => $val_value ) {
				$buffer .= "\t". static::render_array_member( $val_key, $val_value, $longest_key, $localize_strings, $textdomain );
			}
			$buffer .= "],\n";
			return $buffer;
		} elseif ( is_object( $val ) ) {
			$buffer = "{$key} {$padding}=> [\n";
			$object_vars = get_object_vars( $val );
			$longest_key = static::get_longest_key_length( array_keys( $object_vars ) );
			foreach ( $object_vars as $val_key => $val_value ) {
				$buffer .= "\t". static::render_array_member( $val_key, $val_value, $longest_key, $localize_strings, $textdomain );
			}
			$buffer .= "],\n";
			return $buffer;
		}
	}

	/**
	 * Add tabs to the beggining of lines
	 *
	 * @param string $text  The text that will be tabbed (one or multiple lines)
	 * @param integer $tabs The amount of tabs to prepend
	 * @return string       Tabbed text
	 */
	public static function ident( string $text, int $tabs = 1 ) : string {
		$tabs = str_repeat("\t", $tabs);
		return preg_replace( '/^(.*)/m', $tabs .'$1', $text );
	}
}