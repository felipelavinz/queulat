<?php

namespace Queulat;

use Queulat\Forms\Node_Factory;
use Queulat\Forms\Node_Factory_Argument_Handler;

class Bootstrap {
	public function init() {
		add_action(
			'muplugins_loaded', function() {
				( new Generator\Admin\CPT_Plugin() )->init();
			}
		);
		$this->register_default_node_factory_args();
	}
	private function register_default_node_factory_args() {
		$handlers = [
			new Node_Factory_Argument_Handler( 'attributes', 'set_attribute', Node_Factory::CALL_TYPE_KEY_VALUE ),
			new Node_Factory_Argument_Handler( 'label', 'set_label' ),
			new Node_Factory_Argument_Handler( 'name', 'set_name' ),
			new Node_Factory_Argument_Handler( 'options', 'set_options', Node_Factory::CALL_TYPE_VALUE ),
			new Node_Factory_Argument_Handler( 'properties', 'set_property', Node_Factory::CALL_TYPE_KEY_VALUE ),
			new Node_Factory_Argument_Handler( 'value', 'set_value' ),
			new Node_Factory_Argument_Handler( 'text_content', 'set_text_content' ),
			new Node_Factory_Argument_Handler( 'children', 'append_child', Node_Factory::CALL_TYPE_VALUE_ITEMS ),
		];
		foreach ( $handlers as $handler ) {
			Node_Factory::register_argument( $handler );
		}
	}
}
