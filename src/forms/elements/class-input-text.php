<?php

namespace Queulat\Forms\Element;

class Input_Text extends Input {
	protected static $type = 'text';
	public static function __callStatic( $method, $arguments ) {
		var_dump( func_get_args() );
		exit;
		$obj = new static();
		return $obj->$method( ...$arguments );
	}
}
