<?php

namespace Queulat\Forms;

interface Option_Node_Interface {
	/**
	 * Set available options
	 *
	 * @param array|\ArrayIterator $options
	 * @return Node_Interface
	 */
	public function set_options( $options ) : Node_Interface;

	/**
	 * Get available options
	 *
	 * @return array|\ArrayIterator
	 */
	public function get_options();
}
