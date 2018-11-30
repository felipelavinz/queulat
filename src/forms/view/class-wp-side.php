<?php

namespace Queulat\Forms\View;

use Queulat\Forms;
use Queulat\Forms\Element\Input;
use Queulat\Forms\Element_Interface;
use Queulat\Forms\Element\Input_Hidden;
use Queulat\Forms\Properties_Interface;
use Queulat\Forms\Attributes_Interface;
use Queulat\Forms\Form_Element_Interface;

class WP_Side extends Forms\Form_View {
	private $i = 1;
	public function __toString() {
		$out  = '';
		$out .= '<div class="queulat-form">';
		foreach ( $this->form->get_children() as $element ) {
			$this->set_element_view_attributes( $element );
			if ( $element instanceof Input_Hidden ) {
				$out     .= '<div class="hidden">';
					$out .= (string) $element;
				$out     .= '</div>';
			} else {
				$this->set_input_size( $element );
				$out     .= '<div class="control-group">';
					$out .= '<p>';
				if ( is_callable( [ $element, 'get_label' ] ) ) {
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

	/**
	 * Set "view" attributes on each form element
	 *
	 * @param Attributes_Interface $element A form element
	 */
	protected function set_element_view_attributes( Attributes_Interface &$element ) {
		$has_id  = $element->get_attribute( 'id' );
		$form_id = empty( $this->form->get_attribute( 'id' ) ) ? 'side-form' : $this->form->get_attribute( 'id' );
		if ( ! $has_id ) {
			$element->set_attribute( 'id', $form_id . '-' . $this->i );
		}
		$this->i++;
	}
	/**
	 * Automatically set a suitable class for text input fields
	 */
	protected function set_input_size( Attributes_Interface &$element ) {
		if ( ! $element instanceof Input ) {
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

	/**
	 * Show "description" or form help text, if applicable
	 *
	 * @param Properties_Interface $element The form element
	 */
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
