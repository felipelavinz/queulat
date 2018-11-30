<?php

namespace Queulat;

use Queulat\Forms\Node_Factory;
use Queulat\Generator\CLI\Generate_Command;
use Queulat\Forms\Node_Factory_Argument_Handler;

class Bootstrap {
	public function init() {
		add_action(
			'muplugins_loaded', function() {
				( new Generator\Admin\CPT_Plugin() )->init();
			}
		);
		add_action( 'admin_enqueue_scripts', [ $this, 'enqueue_assets' ], 9999 );
		$this->register_default_node_factory_args();
		$this->register_cli_commands();
	}
	public function register_cli_commands() {
		if ( ! defined( 'WP_CLI' ) || ! WP_CLI ) {
			return;
		}
		\WP_CLI::add_command( 'queulat:generate', new Generate_Command );
	}
	public function enqueue_assets() {
		static $asset_versions;
		$versions_path  = __DIR__ .'/../../dist/manifest.json';
		$asset_versions = json_decode( file_get_contents( $versions_path ) );
		wp_enqueue_style( 'queulat-forms', plugins_url( '..' . $asset_versions->{'dist/admin.css'}, __DIR__ ), [], null, 'all' );
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
