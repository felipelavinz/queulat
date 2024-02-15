<?php

namespace Queulat\Validator;

class Min_Length implements Validator_Interface {
	private $min_length = 0;
	private $encoding   = null;
	public function __construct( int $min_length = 1, $encoding = null ) {
		if ( $min_length ) {
			$this->set_min_length( $min_length );
		}
		if ( $encoding ) {
			$this->set_encoding( $encoding );
		} elseif ( function_exists( 'mb_internal_encoding' ) ) {
			$this->set_encoding( mb_internal_encoding() );
		}
	}
	public function set_min_length( $min_length ) {
		$this->min_length = (int) $min_length;
	}
	public function set_encoding( $encoding ) {
		$this->encoding = $encoding;
	}
	public function is_valid( $value ) : bool {
		$val_length = function_exists( 'mb_strlen' ) ? mb_strlen( $value, $this->encoding ) : strlen( $value );
		$val_length = (int) $val_length;
		return $val_length >= $this->min_length;
	}
	public function get_message() : string {
		return sprintf( _x( 'You must type at least %d characters on this field', 'queulat validator message', 'queulat' ), $this->min_length );
	}
}
