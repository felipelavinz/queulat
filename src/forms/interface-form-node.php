<?php
/**
 * Define a high-level interface for form elements.
 *
 * All elements that can have or use a value within a form must implement
 * this interface: inputs, textareas, selects... but also composed elements
 * that are not simple HTML elements -- complex controls such as image
 * galleries, location maps or other javascript-based controls.
 */
namespace Queulat\Forms;

interface Form_Node_Interface {
	/**
	 * Set the node value within the form
	 *
	 * @param  mixed $value  Node value
	 * @return Node_Interface        Reference to the same object
	 */
	public function set_value( $value ) : Node_Interface;

	/**
	 * Get the node value on a given form
	 *
	 * @return mixed Node value
	 */
	public function get_value();

	/**
	 * Set the label for the form element
	 *
	 * @param  string $label ELement label
	 * @return Node_Interface        Reference to the same object
	 */
	public function set_label( string $label ) : Node_Interface;

	/**
	 * Get the label for the form element
	 *
	 * @return string Element label
	 */
	public function get_label() : string;

	/**
	 * Set the name which will be used to submit the form element
	 *
	 * @param  string $name Name attribute for the element
	 * @return Node_Interface       Reference to the same object
	 */
	public function set_name( string $name ) : Node_Interface;

	/**
	 * Get the form element name attribute
	 *
	 * @return string Value of the name attribute for the node
	 */
	public function get_name() : string;
}
