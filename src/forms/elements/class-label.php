<?php

namespace Queulat\Forms\Element;

use Queulat\Forms\HTML_Element;

class Label extends HTML_Element {
	public static function get_element_attributes() : array {
		return [
			'form',
			'for',
		];
	}
	public function get_tag_name() : string {
		return 'label';
	}
	public function __toString() : string {
		return '<label ' . $this->render_attributes() . '>' . $this->get_text_content() . '</label>';
	}
}
