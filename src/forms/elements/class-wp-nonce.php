<?php
/**
 * WP_Nonce form component
 *
 * @package Queulat
 */

namespace Queulat\Forms\Element;

use Queulat\Forms\Form_Component;
use Queulat\Forms\Node_Interface;

/**
 * Create a new WP_Nonce on a form
 */
class WP_Nonce extends Form_Component {

	/**
	 * Build a new WP_Nonce component
	 *
	 * @param string  $action   The nonce action, used to identify and validate it.
	 * @param string  $name     The name that the nonce will use on the request data.
	 * @param boolean $referer Whether to include a "referer" field.
	 */
	public function __construct( string $action = '-1', string $name = '_wpnonce', bool $referer = true ) {
		$this->set_property( 'action', $action );
		$this->set_property( 'name', $name );
		$this->set_property( 'referer', $referer );
	}

	/**
	 * @inheritDoc
	 */
	public function set_value( $value ) : Node_Interface {
		$this->set_property( 'action', $value );
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function get_value() {
		return $this->get_property( 'action' );
	}

	/**
	 * @inheritDoc
	 */
	public function __toString() {
		return wp_nonce_field(
			$this->get_property( 'action' ),
			$this->get_property( 'name' ),
			$this->get_property( 'referer' ),
			false
		);
	}

	/**
	 * @inheritDoc
	 */
	public function set_text_content( string $text ) : Node_Interface {
		return $this;
	}

	/**
	 * @inheritDoc
	 */
	public function get_text_content() : string {
		return '';
	}
}
