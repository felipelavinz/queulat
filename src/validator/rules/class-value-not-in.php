<?php

namespace Queulat\Validator;

class Value_Not_In implements Validator_Interface {
	private $valid_values = [];
	public function __construct( array $valid_values  = [] ) {
		$this->valid_values = $valid_values;
	}
	public function is_valid( $value ): bool {
		return is_array( $value ) ? ! (bool) array_intersect( $value, $this->valid_values ) : ! in_array( $value, $this->valid_values );
	}
	public function get_message(): string {
		return __('The provided value is not valid. Please use a valid value', 'queulat');
	}
}