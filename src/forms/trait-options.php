<?php

namespace Queulat\Forms;

trait Options_Trait {
	protected $options;

	/**
	 * @inheritDoc
	 * @suppress PhanTypeMismatchReturn
	 */
	public function set_options( $options ) : Node_Interface {
		$this->options = $options;
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function get_options() {
		return $this->options;
	}
}
