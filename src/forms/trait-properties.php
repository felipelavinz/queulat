<?php

namespace Queulat\Forms;

use Queulat\Helpers\Arrays;

/**
 * The Properties Trait allows to associate random data with a given node
 */
trait Properties_Trait {

	/**
	 * Hold the value for all defined properties
	 *
	 * @var array
	 */
	protected $properties = [];

	/**
	 * Get any property from this element
	 *
	 * @param string $key The key name for the property
	 * @return mixed The property value (most likely an string)
	 */
	public function get_property( string $key ) {
		return isset( $this->properties[ $key ] ) ? $this->properties[ $key ] : '';
	}

	/**
	 * Set the value of a given property
	 *
	 * @param  string $key   The name of the property
	 * @param  mixed  $value  The value for the property
	 * @return Node_Interface        Reference to the same object
	 * @suppress PhanTypeMismatchReturn*
	 */
	public function set_property( string $key, $value ) : Node_Interface {
		$this->properties[ $key ] = $value;
		return $this;
	}

	/**
	 * Get the values for all defined properties, indexed by name
	 *
	 * @return array Value for all properties
	 */
	public function get_properties() : array {
		return $this->properties;
	}

	/**
	 * Set element properties (attributes and others)
	 *
	 * @param array $properties Set of properties indexed by name
	 * @suppress PhanTypeMismatchReturn*
	 */
	public function set_properties( array $properties ) : Node_Interface {
		$this->init_properties( $properties );
		return $this;
	}

	/**
	 * Set node properties: attributes and others.
	 * Will separate attributes from other types of attributes based on $global_attributes and $element_attributes
	 *
	 * @param array $properties
	 * @suppress PhanUndeclaredMethod
	 */
	protected function init_properties( array $properties ) {
		// recursively filter null values
		$this->properties = Arrays::filter_recursive( $properties );
		if ( $this instanceof Attributes_Interface ) {
			// collect all attributes properties for this element
			$this->collect_attributes();
		}
	}
}
