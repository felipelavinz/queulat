<?php

namespace Queulat\Validator;

interface Validator_Interface {
	public function is_valid( $value ) : bool;
	public function get_message() : string;
}
