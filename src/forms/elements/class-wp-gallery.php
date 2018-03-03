<?php

namespace Queulat\Forms\Element;

use Queulat\Forms;

class WP_Gallery extends WP_Media {
	public function get_defaults() {
		return [
			'title'    => $this->get_label(),
			'multiple' => 'add',
			'button'   => array(
				'text' => _x( 'Select images', 'WP_Gallery button text', 'queulat' ),
			),
			'library'  => array(
				'type' => 'image',
			),
		];
	}
	public function get_upload_button_text() {
		return _x( 'Upload or select some existing images', 'WP_Gallery button text', 'queulat' );
	}

	public function get_remove_button_text() {
		return _x( 'Remove images', 'WP_Gallery button text', 'queulat' );
	}

	public function get_replace_button_text() {
		return _x( 'Replace images', 'WP_Gallery button text', 'queulat' );
	}
}
