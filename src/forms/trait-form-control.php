<?php

namespace Queulat\Forms;

trait Form_Control_Trait {
	protected $label = '';
	protected $name  = '';

	/**
	 * @inheritDoc
	 * @suppress PhanTypeMismatchReturn
	 */
	public function set_label( string $label ) : Node_Interface {
		$this->label = $label;
		return $this;
	}

	public function get_label() : string {
		return $this->label;
	}

	/**
	 * @inheritDoc
	 * @suppress PhanTypeMismatchReturn
	 */
	public function set_name( string $name ) : Node_Interface {
		if ( $this instanceof Attributes_Interface ) {
			$this->set_attribute( 'name', $name );
		} else {
			$this->name = $name;
		}
		return $this;
	}

	public function get_name() : string {
		return $this instanceof Attributes_Interface ? $this->get_attribute( 'name' ) : $this->name;
	}
}
