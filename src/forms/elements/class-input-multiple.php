<?php

namespace Queulat\Forms\Element;

use Queulat\Forms\Form_Component;
use Queulat\Forms\Node_Factory;
use Queulat\Forms\Element\Div;
use Queulat\Forms\Element\Input;
use Queulat\Forms\Element\Button;

class Input_Multiple extends Form_Component {
	public static function get_element_attributes(): array {
		return [
			'class',
			'type',
		];
	}
	public function __toString() : string {
		$attributes = $this->get_attributes();
		// filter attributes for the container.
		$container_attributes = array_intersect( $attributes, array_flip( Div::get_element_attributes() ) );
		$container            = Node_Factory::make(
			Div::class,
			[
				'attributes' => $container_attributes,
			]
		);
		$container->add_class( 'js-queulat-input-multiple' );
		$container->add_class( 'queulat-input-multiple' );
		$input_type = $this->get_attribute( 'type' ) ?: 'text';
		$values     = $this->get_value();
		if ( ( is_array( $values ) && empty( array_filter( $values ) ) ) || empty( $values ) ) {
			$values = [];
		}
		$name = $this->get_name();
		do {
			$value = current( $values );
			if ( ! empty( $values ) && ! $value ) {
				break;
			}
			$container->append_child(
				Node_Factory::make(
					Div::class,
					[
						'attributes' => [
							'class' => 'queulat-input-multiple__item',
						],
						'children'   => [
							Node_Factory::make(
								Div::class,
								[
									'attributes'   => [
										'class' => 'queulat-input-multiple__item-sort',
									],
									'text_content' => '<span class="dashicons dashicons-sort"></span>',
								]
							),
							Node_Factory::make(
								Input::class,
								[
									'attributes' => [
										'class' => 'queulat-input-multiple__input',
										'type'  => $input_type,
										'name'  => $name . '[]',
										'value' => $value ?: '',
									],
								]
							),
							Node_Factory::make(
								Button::class,
								[
									'attributes'   => [
										'type'  => 'button',
										'class' => 'queulat-input-multiple__item-delete button',
									],
									'text_content' => _x( 'Delete value', 'multiple input action', 'queulat' ),
								]
							),
						],
					]
				)
			);
			next( $values );
		} while ( $value );
		$container->append_child(
			Node_Factory::make(
				Div::class,
				[
					'attributes' => [
						'class' => 'queulat-input-multiple__controls',
					],
					'children'   => [
						Node_Factory::make(
							Button::class,
							[
								'attributes'   => [
									'type'  => 'button',
									'class' => 'queulat-input-multiple__add-new button',
								],
								'text_content' => _x( 'Add new', 'multiple input action', 'queulat' ),
							]
						),
					],
				]
			)
		);
		wp_enqueue_script( 'queulat-element-input-multiple', plugins_url( 'js/element-input-multiple.js', __FILE__ ), array( 'jquery', 'jquery-ui-sortable' ) );
		return $container;
	}
}
