<?php

namespace Queulat\Validator;

class Is_Required implements Validator_Interface {
	public function is_valid( $value ) : bool {
		return ! empty( $value );
	}

	public function get_message() : string {
		return __( 'Please complete this field', 'queulat' );
	}
}
