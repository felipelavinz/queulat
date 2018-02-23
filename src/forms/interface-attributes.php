<?php

namespace Queulat\Forms;

/**
 * Attributes are special properties used by objects implementing this interface,
 * such as HTML elements
 */
interface Attributes_Interface {
	/**
	 * Indicate if the element has the specified attribute or not.
	 *
	 * @param  string $attribute_name The name of the attribute you're checking
	 * @return bool                    True if the element has the attribute
	 */
	public function has_attribute( string $attribute_name ) : bool;

	/**
	 * Indicate if the element has one or more of the given attributes.
	 *
	 * @param  array $attribute_names The name of the attributes you're checking
	 * @return bool                     True if the element has at least one of the checked attributes
	 */
	public function has_attributes( array $attribute_names ) : bool;

	/**
	 * Set the value of a named attribute.
	 *
	 * @param  string $name   The name of the attribute
	 * @param  mixed  $value   Value of the attribute
	 * @return Node_Interface Reference to the same object
	 */
	public function set_attribute( string $name, $value ) : Node_Interface;

	/**
	 * Get the value of a given attribute.
	 *
	 * @param string $attribute_name Name of the attribute
	 * @return string Value of the named attribute
	 */
	public function get_attribute( string $attribute_name ) : string;

	/**
	 * Get all attributes associated with this element.
	 *
	 * @return array All the element's attributes
	 */
	public function get_attributes() : array;

	/**
	 * Get a list of all class attributes.
	 *
	 * @return array All element classes
	 */
	public function get_class_list() : array;

	/**
	 * Get a string representing the class of the element.
	 *
	 * @return string Element class as a string
	 */
	public function get_class_name() : string;

	/**
	 * Get the unique ID of the element.
	 *
	 * @return string ID of the element
	 */
	public function get_id() : string;

	/**
	 * Get HTML attributes that apply for any element
	 *
	 * @return array HTML global attributes
	 * @link http://www.w3.org/TR/html5/dom.html#global-attributes
	 */
	public static function get_global_attributes() : array;

	/**
	 * Patterns for composed attributes (e.g: data-src, aria-required).
	 *
	 * @return array
	 */
	public static function get_global_attribute_pattern_prefixes() : array;

	/**
	 * Get a list of attributes from the own element
	 *
	 * @return array List of element attributes
	 */
	public static function get_element_attributes() : array;

	/**
	 * Append a new name to the class attribute
	 *
	 * @param  string $class The new class name
	 * @return Node_Interface        Reference to the same object
	 */
	public function add_class( string $class ) : Node_Interface;

	/**
	 * Remove a class name from the element
	 *
	 * @param  string $class The class name to be removed
	 * @return Node_Interface        Reference to the same object
	 */
	public function remove_class( string $class ) : Node_Interface;
}
