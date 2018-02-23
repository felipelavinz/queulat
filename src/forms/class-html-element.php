<?php

namespace Queulat\Forms;

use Underscore\Types\Arrays;

/**
 * HTML elements are objects that behave like... HTML elements
 */
abstract class HTML_Element implements HTML_Element_Interface {

	use Node_Trait, Attributes_Trait, Properties_Trait;

	/**
	 * Build an HTMLElement
	 *
	 * @param array  $properties   Element properties; most likely attributes but might include other stuff to be used by the view
	 * @param string $text_content Element textual content
	 */
	public function __construct( array $properties = array(), $text_content = '' ) {
		if ( $properties ) {
			$this->init_properties( $properties );
		}
		if ( $text_content ) {
			$this->set_text_content( $text_content );
		}
	}

	public function __get( $key ) {
		if ( 'class' == $key ) {
			return $this->get_class_name();
		}
		if ( 'id' == $key ) {
			return $this->get_id();
		}
		return $this->get_attribute( $key );
	}

	/**
	 * @inheritDoc
	 */
	public static function get_global_attributes() : array {
		return [
			'accesskey',
			'class',
			'contenteditable',
			'dir',
			'hidden',
			'id',
			'lang',
			'spellcheck',
			'style',
			'tabindex',
			'title',
			'translate',
		];
	}

}
