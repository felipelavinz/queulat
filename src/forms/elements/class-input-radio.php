<?php

namespace Queulat\Forms\Element;

use Queulat\Forms;
use Underscore\Types\Arrays;

class Input_Radio extends Input implements Forms\Option_Node_Interface {
	use Forms\Options_Trait;
	public function __toString() : string {
		$options = $this->get_options();
		if ( ! Arrays::isAssociative( $options ) ) {
			$options = array_combine( (array) $options, (array) $options );
		}
		$out  = '<div' . $this->render_attributes() . '>';
		$out .= '<ul>';
		foreach ( $options as $value => $label ) {
			$out .= $this->render_element( $value, $label );
		}
		$out .= '</ul>';
		$out .= '</div>';
		return $out;
	}
	public function render_element( $value, $label ) {
		static $i = 1;
		$name     = $this->get_name();
		$checked  = $this->get_value() != '' && $value == $this->get_value() ? ' checked="checked"' : '';
		$required = $this->get_attribute( 'required' ) ? ' required' : '';
		$out      = '';
		$out     .= '<li>';
		$out     .= '<label>';
		$out     .= "<input type=\"radio\" name=\"{$name}\" id=\"{$this->get_id()}-{$i}\" value=\"" . esc_attr( (string) $value ) . "\"{$checked}{$required}> ";
		$out     .= $label . '';
		$out     .= '</label>';
		$out     .= '</li>';
		++$i;
		return $out;
	}
}
