<?php

namespace Queulat\Forms\Element;

use Queulat\Forms;
use Queulat\Forms\Properties_Trait;
use Queulat\Forms\Childless_Node_Trait;

class WP_Editor extends Forms\Form_Component {
	use Properties_Trait, Childless_Node_Trait;

	private $args = array();

	public function get_tag_name() {
		return 'wp-media';
	}

	public function get_default_args() {
		return [
			'wpautop'       => true,
			'media_buttons' => true,
			'teeny'         => false,
			'dfw'           => false,
			'tinymce'       => true,
			'quicktags'     => true,
		];
	}

	public function set_args( $args = array() ) {
		$this->args = wp_parse_args( $args, $this->get_default_args() );
		return $this;
	}

	public function get_args() {
		return $this->args;
	}

	private function sanitize_id() {
		$id = $this->get_id() . $this->get_name();
		$id = preg_replace( '/[^a-z]/', '', $id );
		return strtolower( $id );
	}

	public function __toString() {
		ob_start();
		$args = $this->get_args();
		if ( empty( $args['textarea_name'] ) ) {
			$args['textarea_name'] = $this->get_name();
		}
		wp_editor( $this->get_value(), $this->sanitize_id(), $args );
		return ob_get_clean();
	}

}
