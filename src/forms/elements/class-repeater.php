<?php
/**
 * Repeater form field
 *
 * Acts as a wrapper for other fields to enable repetition.
 *
 * @package Queulat
 */
namespace Queulat\Forms\Element;

use Queulat\Forms\Node_Trait;
use Queulat\Forms\Node_Factory;
use Queulat\Forms\Form_Component;
use Queulat\Forms\Node_Interface;
use Queulat\Forms\Form_Node_Interface;

class Repeater extends Form_Component implements Node_Interface {

	private static $i = 0;

	use Node_Trait;

	public function __toString() {
		++static::$i;

		$container = Node_Factory::make(
			Div::class,
			[
				'attributes' => [
					'class' => 'js-queulat-repeater queulat-repeater',
				]
			]
		);

		$values = (array) $this->get_value();

		$row_index = 0;
		do {
			$row_values = current( $values );

			$row = Node_Factory::make(
				Div::class,
				[
					'attributes' => [
						'class'    => 'js-queulat-repeater__row queulat-repeater__row',
						'data-row' => $row_index
					]
				]
			);
			$children_index = 0;
			foreach ( $this->get_children() as $prototype_child ) {
				++$children_index;
				$child = clone $prototype_child;
				$group = Node_Factory::make(
					Div::class,
					[
						'attributes' => [
							'class' => 'queulat-repeater__group'
						]
					]
				);
				if ( $child instanceof Form_Node_Interface ) {
					$group->append_child(
						Node_Factory::make(
							Div::class,
							[
								'attributes' => [
									'class' => 'queulat-repeater__label'
								],
								'children' => [
									Node_Factory::make(
										Label::class,
										[
											'attributes' => [
												'for' => 'queulat-repeater__element--'. static::$i .'-'. $row_index .'-'. $children_index
											],
											'text_content' => $child->get_label()
										]
									)
								]
							]
						)
					);
				}

				if ( isset( $row_values[ $child->get_name() ] ) ) {
					$child->set_value( $row_values[ $child->get_name() ] );
				}

				$child->set_attribute( 'data-name', $this->get_name() .'[__i__]['. $child->get_name() .']' );
				$child->set_attribute( 'data-row', $row_index );
				$child->set_name( $this->get_name() .'['. $row_index .']['. $child->get_name() .']' );
				$child->add_class( 'js-queulat-repeater__control' );

				$group->append_child(
					Node_Factory::make(
						Div::class,
						[
							'attributes' => [
								'class' => 'queulat-repeater__control',
							],
							'children' => [
								$child
							]
						]
					)
				);
				$row->append_child( $group );
			}
			$row->append_child(
				Node_Factory::make(
					Div::class,
					[
						'attributes' => [
							'class' => 'queulat-repeater__row-actions'
						],
						'children'   => [
							Node_Factory::make(
								Button::class,
								[
									'text_content' => sprintf( _x( '%s Move Up', 'repeater', 'queulat' ), '<span class="dashicons dashicons-arrow-up-alt2"></span>' ),
									'attributes' => [
										'type'  => 'button',
										'class' => 'js-queulat-repeater__up button'
									]
								]
							),
							Node_Factory::make(
								Button::class,
								[
									'text_content' => sprintf( _x( '%s Move Down', 'repeater', 'queulat' ), '<span class="dashicons dashicons-arrow-down-alt2"></span>' ),
									'attributes' => [
										'type'  => 'button',
										'class' => 'js-queulat-repeater__down button'
									]
								]
							),
							Node_Factory::make(
								Button::class,
								[
									'text_content' => _x( 'Remove group', 'repeater', 'queulat' ),
									'attributes'   => [
										'type'  => 'button',
										'class' => 'js-queulat-repeater__remove button button-link-delete'
									]
								]
							),
						]
					]
				)
			);
			$container->append_child( $row );

			++$row_index;

		} while ( next( $values ) );

		$container->append_child( Node_Factory::make(
			Div::class,
			[
				'attributes' => [
					'class' => 'queulat-repeater__actions'
				],
				'children' => [
					Node_Factory::make(
						Button::class,
						[
							'attributes' => [
								'type' => 'button',
								'class' => 'js-queulat-repeater__add button button-primary'
							],
							'text_content' => sprintf( _x('%s New group', 'repeater', 'queulat' ), '<span class="dashicons dashicons-plus"></span>' )
						]
					)
				]
			]
		) );

		wp_enqueue_script( 'queulat-element-wp-media', plugins_url( 'js/element-repeater.js', __FILE__ ), array( 'jquery' ) );

		return (string) $container;
	}
}