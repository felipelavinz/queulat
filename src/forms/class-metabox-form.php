<?php

namespace Queulat\Forms;

class Metabox_Form extends Element\Form {
	public function __toString() : string {
		$view_class = $this->get_view();
		$view       = new $view_class( $this );
		return (string) $view;
	}
}
