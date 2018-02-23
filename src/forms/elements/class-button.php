<?php

namespace Queulat\Forms\Element;

use Queulat\Forms\Node_List;
use Queulat\Forms\Node_Interface;
use Queulat\Forms\HTML_Form_Element;
use Queulat\Forms\Node_List_Interface;

class Button extends HTML_Form_Element {
	public static function get_element_attributes() : array {
		return [
			'autofocus',
			'autocomplete',
			'disabled',
			'form',
			'formaction',
			'formenctype',
			'formmethod',
			'formnovalidate',
			'formtarget',
			'name',
			'type',
			'value',
		];
	}
	public function __construct( array $properties = array(), $text_content = '' ) {
		parent::__construct( $properties, $text_content );
	}
	public function get_tag_name() : string {
		return 'button';
	}
	public function get_children() : Node_List_Interface {
		return new Node_List();
	}
	public function set_value( $val ) : Node_Interface {
		$this->set_attribute( 'value', $val );
		return $this;
	}
	public function get_value() {
		return $this->get_attribute( 'value' );
	}
	public function __toString() {
		return '<button ' . $this->render_attributes() . '>' . $this->get_text_content() . '</button>';
	}
}
