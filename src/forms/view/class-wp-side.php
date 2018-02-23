<?php

namespace Queulat\Forms\View;

use Queulat\Forms;
use Queulat\Forms\Element;
use Queulat\Forms\Form_Element_Interface;
use Queulat\Forms\Properties_Interface;

class WP_Side extends Forms\Form_View {
	private $i = 1;
	public function __toString() {
		$out  = '';
		$out .= '<div class="queulat-form">';
		foreach ( $this->form->get_children() as $element ) {
			$this->set_element_view_attributes( $element );
			if ( $element instanceof Element\Input_Hidden ) {
				$out     .= '<div class="hidden">';
					$out .= (string) $element;
				$out     .= '</div>';
				// } elseif ( $element instanceof Element\Input_Button || $element instanceof Element\Button || ! method_exists($element, 'getLabel') ) {
				// $out .= '<tr>';
				// $out .= '<td colspan="2">';
				// $out .= (string)$element;
				// $out .= '</td>';
				// $out .= '</tr>';
			} else {
				$this->set_input_size( $element );
				$out     .= '<div class="control-group">';
					$out .= '<p>';
				if ( $element instanceof Form_Element_Interface ) {
					$out .= '<label for="' . $element->get_attribute( 'id' ) . '">' . $element->get_label() . '</label>';
				}
						$out .= (string) $element;
						$out .= $this->get_element_description( $element );
					$out     .= '</p>';
				$out         .= '</div>';
			}
		}
		$out .= '</div>';
		return $out;
	}
	protected function set_element_view_attributes( Forms\Element_Interface &$element ) {
		$has_id = $element->get_attribute( 'id' );
		if ( ! $has_id ) {
			$element->set_attribute( 'id', $this->form->get_attribute( 'id' ) . '-' . $this->i );
		}
		// if ( $element instanceof Element\InputSubmit || $element instanceof Element\Button && $element->getAttribute('type') === 'submit' ) {
		// $element->setAttribute('class', 'button-primary');
		// } elseif ( $element instanceof Element\InputButton || $element instanceof Element\Button ) {
		// $element->setAttribute('class', 'button');
		// }
		$this->i++;
	}
	/**
	 * Automatically set a suitable class for text input fields
	 */
	protected function set_input_size( Forms\Element_Interface &$element ) {
		if ( ! $element instanceof Element\Input ) {
			return;
		}

		if ( $element->get_class_name() ) {
			return;
		}

		$maxlength = $element->get_attribute( 'maxlength' );
		if ( ! empty( $maxlength ) ) {
			if ( $maxlength < 6 ) {
				$element->set_attribute( 'class', 'small-text' );
				return;
			}
		}

		if ( ! $element->get_attribute( 'size' ) ) {
			$element->set_attribute( 'class', 'widefat' );
		}
		return;
	}
	protected function get_element_description( Properties_Interface $element ) {
		if ( method_exists( $element, 'get_property' ) ) {
			$description = $element->get_property( 'description' );
			$show_inline = $element->get_property( 'description_inline' );
		}
		if ( $description ) {
			return $show_inline ? ' <span class="description">' . $description . '</span>' : '<p class="description">' . $description . '</p>';
		}
	}
}
