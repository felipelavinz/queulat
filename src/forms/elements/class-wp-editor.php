<?php

namespace Queulat\Forms\Element;

use Queulat\Forms\Form_Component;
use Queulat\Forms\Properties_Trait;
use Queulat\Forms\Childless_Node_Trait;

class WP_Editor extends Form_Component {
	public function get_default_properties() {
		return [
			'wpautop'       => true,
			'media_buttons' => true,
			'teeny'         => false,
			'dfw'           => false,
			'tinymce'       => true,
			'quicktags'     => true,
		];
	}

	private function sanitize_id() {
		$id = $this->get_id() . $this->get_name();
		$id = preg_replace( '/[^a-z]/', '', $id );
		return strtolower( $id );
	}

	public function __toString() {
		ob_start();
		$args = wp_parse_args( $this->get_properties(), $this->get_default_properties() );
		if ( empty( $args['textarea_name'] ) ) {
			$args['textarea_name'] = $this->get_name();
		}
		wp_editor( $this->get_value(), $this->sanitize_id(), $args );
		return ob_get_clean();
	}

}
