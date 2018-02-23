<?php

namespace Queulat\Validator;

class Is_Boolean implements Validator_Interface {

	private static $allowed_values = array(
		'true',
		'false',
		'on',
		'off',
		'yes',
		'no',
		'1',
		'0',
	);

	private $allow_empty = false;

	public function __construct( $allow_empty = false ) {
		$this->allow_empty = (bool) $allow_empty;
	}

	public function is_valid( $value ) : bool {
		$value = (string) trim( $value );
		if ( $this->allow_empty ) {
			return ( in_array( $value, static::$allowed_values, true ) || empty( $value ) ) ? true : false;
		} else {
			return in_array( $value, static::$allowed_values, true );
		}
	}

	public function get_message() : string {
		return __( 'The value for this field should be of a true/false type', 'queulat' );
	}

}
