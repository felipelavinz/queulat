<?php

namespace Queulat\Forms;

abstract class Form_Component extends Component implements Form_Node_Interface {

	protected $name = '';
	protected $value;

	public function set_name( string $name ) : Node_Interface {
		$this->name = $name;
		return $this;
	}
	public function get_name() : string {
		return $this->name;
	}
	public function set_value( $value ) : Node_Interface {
		$this->value = $value;
		return $this;
	}
	public function get_value() {
		return $this->value;
	}
}
