<?php

namespace Queulat\Forms;

interface Properties_Interface {

	/**
	 * Get any property from this element
	 *
	 * @param string $key The key name for the property
	 * @return mixed The property value (most likely an string)
	 */
	public function get_property( string $key );

	/**
	 * Set the value of a given property
	 *
	 * @param  string $key   The name of the property
	 * @param  mixed  $value  The value for the property
	 * @return Node_Interface        Reference to the same object
	 * @suppress PhanTypeMismatchReturn*
	 */
	public function set_property( string $key, $value ) : Node_Interface;

	/**
	 * Get the values for all defined properties, indexed by name
	 *
	 * @return array Value for all properties
	 */
	public function get_properties() : array;

	/**
	 * Set element properties (attributes and others)
	 *
	 * @param array $properties Set of properties indexed by name
	 * @suppress PhanTypeMismatchReturn*
	 */
	public function set_properties( array $properties ) : Node_Interface;

}
