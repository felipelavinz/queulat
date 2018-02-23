<?php

namespace Queulat\Forms;

interface Node_List_Interface extends \ArrayAccess, \SeekableIterator, \Countable, \Serializable {
	/**
	 * Get a child node with a given index
	 *
	 * @param  int|string $index The index of the desired item
	 * @return Node_Interface    A node object
	 */
	public function get_item( $index ) : Node_Interface;

	/**
	 * Get a copy of the children nodes as an array
	 *
	 * @return array
	 */
	public function getArrayCopy();
}
