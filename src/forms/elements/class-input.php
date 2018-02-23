<?php

namespace Queulat\Forms\Element;

use Queulat\Forms\Node_Interface;
use Queulat\Forms\HTML_Form_Element;

class Input extends HTML_Form_Element {
	protected static $type;
	public function __construct( array $properties = array(), $text_content = '' ) {
		parent::__construct( $properties, $text_content );
		$this->set_attribute( 'type', static::$type );
	}
	public function get_tag_name() : string {
		return 'input';
	}

	/**
	 * @inheritDoc
	 */
	public static function get_element_attributes() : array {
		return [
			'accept',
			'alt',
			'autocomplete',
			'autofocus',
			'checked',
			'disabled',
			'form',
			'formaction',
			'formenctype',
			'formmethod',
			'formnovalidate',
			'formtarget',
			'height',
			'list',
			'max',
			'maxlength',
			'min',
			'multiple',
			'name',
			'pattern',
			'placeholder',
			'readonly',
			'required',
			'size',
			'src',
			'step',
			'type',
			'value',
			'width',
		];
	}
	public function set_value( $value ) : Node_Interface {
		$this->set_attribute( 'value', $value );
		return $this;
	}
	public function get_value() {
		return $this->get_attribute( 'value' );
	}
	/**
	 * @inheritDoc
	 */
	public function __toString() : string {
		return '<input ' . $this->render_attributes() . '>';
	}
}
