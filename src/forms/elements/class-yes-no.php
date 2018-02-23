<?php

namespace Queulat\Forms\Element;

use Queulat\Forms\Form_Component;

class Yes_No extends Form_Component {
	public function __toString() {
		$select = new Select();
		$select->set_options(
			[
				'1' => _x( 'Yes', '"yes" option', 'queulat' ),
				'0' => _x( 'No', '"no" option', 'queulat' ),
			]
		);
		$select->set_name( $this->get_name() );
		$select->set_value( $this->get_value() );
		return (string) $select;
	}
}
