<?php

namespace Queulat\Forms;

/**
 * The Node_Interface defines the lowest-level of object that can be used with forms
 */
interface Node_Interface {
	/**
	 * Add a new node child
	 *
	 * @param  Node_Interface $node Node object
	 * @return Node_Interface       The appended child
	 */
	public function append_child( Node_Interface $node ) : Node_Interface;

	/**
	 * Remove a child node
	 *
	 * @param  Node_Interface $node The node object to be removed
	 * @return Node_Interface       The removed node
	 */
	public function remove_child( Node_Interface $node ) : Node_Interface;

	/**
	 * Insert the specified node before the reference node as a child of the current node
	 *
	 * @param  Node_Interface $new_node       The new node to be inserted
	 * @param  Node_Interface $reference_node The reference node
	 * @return Node_Interface                 The new inserted node
	 */
	public function insert_before( Node_Interface $new_node, Node_Interface $reference_node ) : Node_Interface;

	/**
	 * Replace a given node with a new one
	 *
	 * @param  Node_Interface $new_child The new node that will replace the old
	 * @param  Node_Interface $old_child The reference node
	 * @return Node_Interface            The replaced node
	 */
	public function replace_child( Node_Interface $new_child, Node_Interface $old_child ) : Node_Interface;

	/**
	 * Get a collection of all child elements of the element
	 *
	 * @return Node_List_Interface Child elements
	 */
	public function get_children() : Node_List_Interface;

	/**
	 * Set the text content of the node. Replaces child nodes
	 *
	 * @param string $text The text content for the node
	 */
	public function set_text_content( string $text ) : Node_Interface;

	/**
	 * Get the text content for the current and child nodes
	 *
	 * @return string Textual content of the node
	 */
	public function get_text_content() : string;

	/**
	 * Get a string representation of the element,
	 * Use (string)$object to get, or echo $object to output
	 *
	 * @return string String representation of the element
	 */
	public function __toString();
}
