<?php

namespace Queulat\Forms\Element;

use Queulat\Forms;
use Underscore\Types\Arrays;
use Queulat\Forms\Node_List;
use Queulat\Forms\Node_Interface;
use Queulat\Forms\Node_List_Interface;

class Select extends Forms\HTML_Form_Element implements Forms\Option_Node_Interface {
	use Forms\Options_Trait;

	protected $value = null;

	/**
	 * Allowed element attributes
	 *
	 * @return array Element Attributes
	 * @link http://www.w3.org/TR/html5/forms.html#the-select-element
	 */
	public static function get_element_attributes() : array {
		return [
			'autofocus',
			'disabled',
			'form',
			'multiple',
			'name',
			'required',
			'size',
		];
	}

	public function get_tag_name() : string {
		return 'select';
	}

	public function get_children() : Node_List_Interface {
		return new Node_List( $this->get_options() );
	}

	public function set_value( $value ) : Node_Interface {
		$this->value = $value;
		return $this;
	}

	public function get_value() {
		return $this->value;
	}

	private function maybe_deep_options( $options ) {
		if ( ! Arrays::isAssociative( $options ) ) {
			$options = array_combine( $options, $options );
		}
		foreach ( $options as $key => $val ) {
			if ( is_array( $val ) && ! Arrays::isAssociative( $val ) ) {
				$options[ $key ] = array_combine( $val, $val );
			}
		}
		return $options;
	}

	public function __toString() {
		$options = $this->get_options();
		$options = $this->maybe_deep_options( $options );
		if ( $this->get_attribute( 'multiple' ) ) {
			$this->set_name( $this->get_name() . '[]' );
		}
		$out = '<select' . $this->render_attributes() . '>';
		foreach ( $options as $key => $val ) {
			if ( is_array( $val ) ) {
				$out .= '<optgroup label="' . esc_attr( $key ) . '">';
				foreach ( $val as $value => $label ) {
					$out .= $this->render_option( $value, $label );
				}
				$out .= '</optgroup>';
			} else {
				$out .= $this->render_option( $key, $val );
			}
			$out .= '';
		}
		$out .= '</select>';
		return $out;
	}

	private function render_option( $value, $label ) {
		$selections = (array) $this->get_value();
		$selected   = in_array( $value, $selections ) ? ' selected="selected"' : '';
		return '<option value="' . esc_attr( $value ) . '"' . $selected . '>' . $label . '</option>';
	}
}
