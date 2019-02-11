<?php

namespace Queulat\Forms;

trait Node_Trait {
	/**
	 * Hold the child nodes of the current object
	 *
	 * @var Node_List_Interface
	 */
	protected $children = null;

	/**
	 * Hold the text_content for the current object
	 *
	 * @var string
	 */
	protected $text_content = '';

	/**
	 * Add a new node child
	 *
	 * @param  Node_Interface $node Node object
	 * @return Node_Interface       The appended child
	 * @suppress PhanTypeMismatchReturn
	 */
	public function append_child( Node_Interface $node ) : Node_Interface {
		$children   = $this->get_children();
		$children[] = $node;
		return $node;
	}

	/**
	 * Add several new child nodes. Useful for chaining
	 *
	 * @param iterable $nodes Several new nodes
	 * @return Node_Interface Reference to the same element
	 * @suppress PhanTypeMismatchReturn
	 */
	public function append_children( $nodes ) : Node_Interface {
		foreach ( $nodes as $node ) {
			$this->append_child( $node );
		}
		return $this;
	}

	/**
	 * Remove a child node
	 *
	 * @param  Node_Interface $node The node object to be removed
	 * @return Node_Interface       The removed node
	 */
	public function remove_child( Node_Interface $node ) : Node_Interface {
		$this->get_children();
		foreach ( $this->children as $key => $child ) {
			if ( $node == $child ) {
				unset( $this->children[ $key ] );
				return $node;
			}
		}
	}

	/**
	 * Insert the specified node before the reference node as a child of the current node
	 *
	 * @param  Node_Interface $new_node       The new node to be inserted
	 * @param  Node_Interface $reference_node The reference node
	 * @return Node_Interface                 The new inserted node
	 */
	public function insert_before( Node_Interface $new_node, Node_Interface $reference_node ) : Node_Interface {
		$this->get_children();
		$children_array     = $this->children->getArrayCopy();
		$reference_position = array_search( $reference_node, $children_array );
		$first_half         = array_slice( $children_array, 0, $reference_position );
		$first_half[]       = $new_node;
		$second_half        = array_slice( $children_array, $reference_position );
		$this->children     = new Node_List( array_merge( $first_half, $second_half ) );
		return $new_node;
	}

	/**
	 * Replace a given node with a new one
	 *
	 * @param  Node_Interface $new_child The new node that will replace the old
	 * @param  Node_Interface $old_child The reference node
	 * @return Node_Interface            The replaced node
	 */
	public function replace_child( Node_Interface $new_child, Node_Interface $old_child ) : Node_Interface {
		$this->get_children();
		$children_array                        = $this->children->getArrayCopy();
		$old_child_position                    = array_search( $old_child, $children_array );
		$old_child                             = $this->children[ $old_child_position ];
		$this->children[ $old_child_position ] = $new_child;
		return $old_child;
	}

	/**
	 * Get a collection of all child elements of the element
	 *
	 * @return Node_List_Interface Child elements
	 */
	public function get_children() : Node_List_Interface {
		if ( $this->children instanceof Node_List_Interface ) {
			return $this->children;
		}
		$this->children = new Node_List();
		return $this->children;
	}

	/**
	 * Set the text content of the node. Replaces child nodes
	 *
	 * @param string $text    The text content for the node
	 * @return Node_Interface The current node
	 * @suppress PhanTypeMismatchReturn
	 */
	public function set_text_content( string $text ) : Node_Interface {
		$this->children     = null;
		$this->text_content = $text;
		return $this;
	}

	/**
	 * Get the text content for the current and child nodes
	 *
	 * @return string Textual content of the node
	 */
	public function get_text_content() : string {
		return $this->text_content;
	}
}
