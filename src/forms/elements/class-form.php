<?php

namespace Queulat\Forms\Element;

use Queulat\Forms\HTML_Element;

class Form extends HTML_Element {
	protected static $valid_enctype = array(
		'application/x-www-form-urlencoded',
		'multipart/form-data',
		'text/plain',
	);
	protected $view                 = '';
	public static function get_element_attributes() : array {
		return [
			'accept-charset',
			'action',
			'autocomplete',
			'enctype',
			'method',
			'name',
			'novalidate',
			'target',
		];
	}
	public function get_tag_name() : string {
		return 'form';
	}
	public function set_view( $view_class ) : Form {
		if ( ! in_array( 'Queulat\Forms\View_Interface', class_implements( $view_class ) ) ) {
			throw new \InvalidArgumentException();
		}
		$this->view = $view_class;
		return $this;
	}
	public function get_view() : string {
		if ( empty( $this->view ) ) {
			return apply_filters( 'queulat_form_default_view', '\Queulat\Forms\View\WP_Wide' );
		} else {
			return $this->view;
		}
	}
	public function __toString() : string {
		$out      = '';
		$__v      = $this->get_view();
		$view     = new $__v( $this );
		$out     .= '<form' . $this->render_attributes() . '>';
			$out .= $view;
		$out     .= '</form>';
		return $out;
	}
}
