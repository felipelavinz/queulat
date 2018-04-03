<?php

namespace Queulat\Forms\Element;

use Queulat\Forms\Options_Trait;
use Queulat\Forms\Form_Component;
use Queulat\Forms\Option_Node_Interface;

class UI_Select2 extends Form_Component implements Option_Node_Interface {
	use Options_Trait;
	public function __toString() {
		$this->add_class( 'select-2' );
		$instance = array();
		if ( $this->get_property( 'instance' ) ) {
			$instance = $this->get_property( 'instance' );
		} elseif ( $this->has_attribute( 'data-select2' ) ) {
			$raw_data = $this->get_attribute( 'data-select2' );
			// attribute value should be json-encoded
			$instance = is_string( $raw_data ) ? json_decode( $this->get_attribute( 'data-select2' ) ) : $raw_data;
		}
		$this->set_attribute( 'data-select2-args', $instance );

		// it could have some extra values, so let's add them to the available options
		if ( ! array_intersect( array_keys( (array) $this->get_options() ), (array) $this->get_value() ) ) {
			$added_options = array_combine( (array) $this->get_value(), (array) $this->get_value() );
			$this->set_options( (array) $this->get_options() + $added_options );
		}

		static::enqueue_assets();

		$select = new Select;
		$select->set_attributes( $this->get_attributes() );
		$select->add_class( 'select-2' );
		if ( isset( $this->get_property('instance')['multiple'] ) && $this->get_property('instance')['multiple'] ) {
			$select->set_attribute('multiple', 'multiple');
		}
		$select->set_name( $this->get_name() );
		$select->set_options( $this->get_options() );
		$select->set_value( $this->get_value() );

		return $select->__toString();
	}

	public static function register_assets() {
		$current_locale = apply_filters( 'queulat_forms_element_ui_select2_locale', current( explode( '_', get_locale() ) ) );
		wp_register_style( 'select2-css', queulat_url( 'node_modules/select2/dist/css/select2.min.css' ), array(), '4.0.1' );
		wp_register_script( 'select2-js', queulat_url( 'node_modules/select2/dist/js/select2.min.js' ), array( 'jquery' ), '4.0.1', true );
		wp_register_script( 'select2-i18n', queulat_url( 'node_modules/select2/dist/js/i18n/' . $current_locale . '.js' ), array( 'select2-js' ), '4.0.1', true );
		wp_register_script( 'select2-custom-js', queulat_url( 'src/forms/elements/js/element-ui-select2.js' ), array( 'select2-js' ) );
	}

	public static function enqueue_assets() {
		static::register_assets();
		wp_enqueue_style( 'select2-css' );
		wp_enqueue_script( 'select2-js' );
		wp_enqueue_script( 'select2-i18n' );
		wp_enqueue_script( 'select2-custom-js' );
	}
}
