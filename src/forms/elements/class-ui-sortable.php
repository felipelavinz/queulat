<?php

namespace Queulat\Forms\Element;

use Queulat\Forms\Node_Factory;
use Queulat\Forms\Options_Trait;
use Queulat\Forms\Form_Component;
use Queulat\Forms\Option_Node_Interface;

class UI_Sortable extends Form_Component implements Option_Node_Interface {
	use Options_Trait;
	public function __toString() {
		$this->add_class( 'queulat-sortable' );
		$this->add_class( 'js-queulat-sortable' );

		$sortables = [];
		foreach ( $this->get_options() as $key => $val ) {
			$sortables[] = $this->build_item( $val, $key );
		}

		$container = Node_Factory::make(
			Div::class,
			[
				'attributes' => $this->get_attributes(),
				'children'   => $sortables
			]
		);

		wp_enqueue_script( 'queulat-sortable', queulat_url( 'src/forms/elements/js/element-js-ui-sortable.js' ), [ 'jquery-ui-sortable' ] );

		return (string) $container;
	}
	protected function build_item( $label, $value = '' ) {
		return Node_Factory::make(
			Div::class,
			[
				'attributes' => [
					'class' => 'menu-item-bar'
				],
				'children' => [
					Node_Factory::make(
						Div::class,
						[
							'attributes' => [
								'class' => 'menu-item-handle'
							],
							'text_content' => '<span class="item-title">'. esc_html( $label ) .'</span><span class="item-controls"><span class="item-type"><span class="dashicons dashicons-sort"></span></span></span>',
							'children' => [
								Node_Factory::make(
									Input_Hidden::class,
									[
										'name' => $this->get_name() .'[]',
										'value' => ! empty( $value ) ? esc_attr( $value ) : esc_attr( $label )
									]
								)
							]
						]
					)
				]
			]
		);
	}
}
