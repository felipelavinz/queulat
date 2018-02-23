<?php

namespace Queulat\Validator;

class Is_Email implements Validator_Interface {
	public function is_valid( $value ) : bool {
		return is_email( $value );
	}
	public function get_message() : string {
		return __( 'Please enter a valid e-mail address', 'queulat' );
	}
}
