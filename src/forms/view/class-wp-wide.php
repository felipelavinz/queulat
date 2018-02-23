<?php

namespace Queulat\Forms\View;

use Queulat\Forms;
use Queulat\Forms\Element;
use Queulat\Forms\Element\Fieldset;
use Queulat\Forms\Element_Interface;
use Queulat\Forms\Element\Input_Text;
use Queulat\Forms\Component_Interface;
use Queulat\Forms\Form_Node_Interface;
use Queulat\Forms\Form_Element_Interface;
use Queulat\Forms\Attributes_Interface;
use Queulat\Forms\Properties_Interface;

class WP_Wide extends Forms\Form_View {
	private $i = 1;
	public function __toString() {
		$out      = '';
		$out     .= '<table class="form-table queulat-form">';
			$out .= $this->render_elements( $this->form->get_children() );
		$out     .= '</table>';
		return $out;
	}
	public function render_elements( $elements ) {
		$out = '';
		foreach ( $elements as $element ) {
			if ( $element instanceof Fieldset ) {
				$out                 .= '<tr class="fieldset">';
					$out             .= '<th scope="row">' . $element->get_property( 'label' ) . '</th>';
					$out             .= '<td>';
						$out         .= '<fieldset class="queulat-form__fieldset">';
							$out     .= '<table class="fieldset-elements">';
								$out .= $this->render_elements( $element->get_children() );
							$out     .= '</table>';
						$out         .= '</fieldset>';
						$out         .= $this->get_element_description( $element );
					$out             .= '</td>';
				$out                 .= '</tr>';
			} elseif ( $element instanceof Element_Interface || $element instanceof Form_Node_Interface ) {
				$this->set_element_view_attributes( $element );
				if ( $element instanceof Element\Input_Hidden ) {
					$out         .= '<tr class="hidden">';
						$out     .= '<td colspan="2">';
							$out .= (string) $element;
						$out     .= '</td>';
					$out         .= '</tr>';
					// } elseif ( $element instanceof Element\Input_Button || $element instanceof Element\Button || ! method_exists($element, 'getLabel') ) {
					// $out .= '<tr>';
					// $out .= '<td colspan="2">';
					// $out .= (string)$element;
					// $out .= '</td>';
					// $out .= '</tr>';
				} elseif ( $element instanceof Form_Node_Interface ) {
					if ( $element instanceof Form_Element_Interface ) {
						$this->set_input_size( $element );
					}
					$out         .= '<tr>';
						$out     .= '<th scope="row">';
							$out .= '<label for="' . $element->get_attribute( 'id' ) . '">' . $element->get_label() . '</label>';
						$out     .= '</th>';
						$out     .= '<td>';
							$out .= (string) $element;
							$out .= $this->get_element_description( $element );
						$out     .= '</td>';
					$out         .= '</tr>';
				} else {
					if ( $element instanceof Form_Element_Interface ) {
						$this->set_input_size( $element );
					}
					$out         .= '<tr>';
						$out     .= '<td colspan="2">';
							$out .= (string) $element;
							$out .= $this->get_element_description( $element );
						$out     .= '</td>';
					$out         .= '</tr>';
				}
			} elseif ( $element instanceof Component_Interface ) {
				$out .= '<tr><td colspan="2">' . (string) $element . '</tr></td>';
			}
		}
		return $out;
	}
	protected function set_element_view_attributes( Attributes_Interface &$element ) {
		$has_id  = $element->get_attribute( 'id' );
		if ( ! $has_id ) {
			$element->set_attribute( 'id', $this->form->get_attribute( 'id' ) . '-element-' . $this->i );
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
	protected function set_input_size( Element_Interface &$element ) {
		if ( ! $element instanceof Input_Text ) {
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
			if ( $maxlength > 40 ) {
				$element->set_attribute( 'class', 'widefat' );
				return;
			}
		}

		if ( ! $element->get_attribute( 'size' ) ) {
			$element->set_attribute( 'class', 'regular-text' );
		}
		return;
	}
	protected function get_element_description( Properties_Interface $element ) {
		$description = $element->get_property( 'description' );
		$show_inline = $element->get_property( 'description_inline' );
		if ( $description ) {
			return $show_inline ? ' <span class="description">' . $description . '</span>' : '<p class="description">' . $description . '</p>';
		}
	}
}
