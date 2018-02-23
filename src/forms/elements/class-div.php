<?php

namespace Queulat\Forms\Element;

use Queulat\Forms\HTML_Element;

class Div extends HTML_Element {
	/**
	 * @inheritDoc
	 */
	public function get_tag_name() : string {
		return 'div';
	}

	/**
	 * @inheritDoc
	 */
	public function __toString() : string {
		$out = '<'. $this->get_tag_name() . $this->render_attributes() .'>';
			$out .= $this->get_text_content();
			foreach ( $this->get_children() as $child ) {
				$out .= (string) $child;
			}
		$out .= '</'. $this->get_tag_name() .'>';
		return $out;
	}
}