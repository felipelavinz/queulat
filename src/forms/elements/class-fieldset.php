<?php

namespace Queulat\Forms\Element;

use Queulat\Forms\Node_Interface;
use Queulat\Forms\HTML_Element;

class Fieldset extends HTML_Element {
	public function get_tag_name() : string {
		return 'fieldset';
	}
	public static function get_element_attributes(): array {
		return [
			'disabled',
			'form',
			'name',
		];
	}
	public function __toString() {
		return '';
	}
}
