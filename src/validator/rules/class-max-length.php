<?php

namespace Queulat\Validator;

class Max_Length implements Validator_Interface {
	private $max_length = 0;
	private $encoding   = null;
	public function __construct( int $max_length = 0, $encoding = null ) {
		if ( $max_length ) {
			$this->set_max_length( $max_length );
		}
		if ( $encoding ) {
			$this->set_encoding( $encoding );
		} elseif ( function_exists( 'mb_internal_encoding' ) ) {
			$this->set_encoding( mb_internal_encoding() );
		}
	}
	public function set_max_length( $max_length ) {
		$this->max_length = (int) $max_length;
	}
	public function set_encoding( $encoding ) {
		$this->encoding = $encoding;
	}
	public function is_valid( $value ) : bool {
		$val_length = function_exists( 'mb_strlen' ) ? mb_strlen( $value, $this->encoding ) : strlen( $value );
		$val_length = (int) $val_length;
		return $val_length <= $this->max_length;
	}
	public function get_message() : string {
		return sprintf( _x( 'You can only type up to %d characters on this field', 'queulat validator message', 'queulat' ), $this->max_length );
	}
}
