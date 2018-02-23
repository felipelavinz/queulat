<?php

namespace Queulat\Forms;

/**
 * An element is an Node subclass that supports attributes and has an specific tag name.
 * The most common type of Element are HTML elements
 */
interface Element_Interface extends Node_Interface, Attributes_Interface {

	/**
	 * Get the name of the tag for the given element.
	 *
	 * @return string Tag name of the element
	 */
	public function get_tag_name() : string;

}
