<?php

namespace Queulat\Forms;

abstract class Component implements Component_Interface, Form_Node_Interface, Properties_Interface {

	protected $text_content = '';

	use Form_Control_Trait, Properties_Trait, Childless_Node_Trait, Attributes_Trait;

	/**
	 * @inheritDoc
	 * @internal Components are not supposed to have global attributes
	 */
	public static function get_global_attributes() : array {
		return [];
	}

	/**
	 * @inheritDoc
	 */
	public function set_text_content( string $text ) : Node_Interface {
		$this->text_content = $text;
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function get_text_content() : string {
		return $this->text_content;
	}
}
