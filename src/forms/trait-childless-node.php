<?php
/**
 * Helps implementing the Node_Interface for elments or components that have no children
 */

namespace Queulat\Forms;

trait Childless_Node_Trait {
	/**
	 * @inheritDoc
	 * @suppress PhanTypeMismatchReturn
	 */
	public function append_child( Node_Interface $node ) : Node_Interface {
		return $this;
	}

	/**
	 * @inheritDoc
	 * @suppress PhanTypeMismatchReturn
	 */
	public function remove_child( Node_Interface $node ) : Node_Interface {
		return $this;
	}

	/**
	 * @inheritDoc
	 * @suppress PhanTypeMismatchReturn
	 */
	public function insert_before( Node_Interface $new_node, Node_Interface $reference_node ) : Node_Interface {
		return $this;
	}

	/**
	 * @inheritDoc
	 * @suppress PhanTypeMismatchReturn
	 */
	public function replace_child( Node_Interface $new_child, Node_Interface $old_child ) : Node_Interface {
		return $this;
	}

	/**
	 * @inheritDoc
	 * @suppress PhanTypeMismatchReturn
	 */
	public function get_children() : Node_List_Interface {
		return new Node_List();
	}
}
