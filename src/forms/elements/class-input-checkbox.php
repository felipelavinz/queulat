<?php

namespace Queulat\Forms\Element;

use Queulat\Forms;
use Underscore\Types\Arrays;
use Queulat\Forms\Options_Trait;
use Queulat\Forms\Node_Interface;
use Queulat\Forms\Option_Node_Interface;

class Input_Checkbox extends Input implements Option_Node_Interface {
	protected $value;
	use Options_Trait;
	public function set_value( $value ) : Node_Interface {
		$this->value = $value;
		return $this;
	}
	public function get_value() {
		return $this->value;
	}
	public function __toString() : string {
		$options = $this->get_options();
		if ( ! Arrays::isAssociative( $options ) ) {
			$options = array_combine( (array) $options, (array) $options );
		}
		$out      = '<div' . $this->render_attributes() . '>';
			$out .= '<ul>';
		foreach ( $options as $value => $label ) {
			$out .= $this->render_element( $value, $label );
		}
			$out .= '</ul>';
		$out     .= '</div>';
		return $out;
	}
	public function render_element( $value, $label ) {
		static $i     = 1;
		$name         = count( $this->get_options() ) > 1 ? $this->get_name() . '[]' : $this->get_name();
		$checked      = in_array( $value, (array) $this->get_value() ) ? ' checked="checked"' : '';
		$out          = '';
		$out         .= '<li>';
			$out     .= '<label>';
				$out .= '<input type="checkbox" name="' . $name . '" id="' . $this->get_id() . '-' . $i . '" value="' . esc_attr( $value ) . '"' . $checked . '> ';
				$out .= $label;
			$out     .= '</label>';
		$out         .= '</li>';
		++$i;
		return $out;
	}
}
