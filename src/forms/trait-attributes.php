<?php

namespace Queulat\Forms;

use Underscore\Types\Arrays;

/**
 * The Attributes_Trait helps in implementing all of the required methods for an
 * element supporting HTML attributes
 */
trait Attributes_Trait {

	/**
	 * Element's own attributes.
	 *
	 * @var array
	 */
	protected static $element_attributes = array();

	/**
	 * Collected attributes
	 *
	 * @var array
	 */
	protected $attributes = array();

	/**
	 * @inheritDoc
	 */
	public static function get_global_attribute_pattern_prefixes() : array {
		return [
			'data',
			'aria',
		];
	}

	/**
	 * @inheritDoc
	 */
	public static function get_element_attributes() : array {
		return [];
	}

	/**
	 * @inheritDoc
	 */
	public static function get_global_attributes() : array {
		return [];
	}

	/**
	 * @inheritDoc
	 */
	public function has_attribute( string $key ) : bool {
		return isset( $this->attributes[ $key ] );
	}

	/**
	 * @inheritDoc
	 */
	public function has_attributes( array $keys ) : bool {
		return (bool) array_intersect( array_keys( $this->get_attributes() ), $keys );
	}

	/**
	 * Set the value of a named attribute.
	 *
	 * Since it returns a reference to the same element, you can use it
	 * on a "fluid" way:
	 * $obj->set_attribute( 'foo', 'bar' )->set_attribute( 'lorem', 'ipsum' );
	 *
	 * @param string $attr    The name of the attribute
	 * @param mixed  $value   Value of the attribute
	 * @return Node_Interface Reference to the same element
	 * @suppress PhanTypeMismatchReturn
	 */
	public function set_attribute( string $attr, $value ) : Node_Interface {
		$this->attributes[ $attr ] = $value;
		return $this;
	}

	/**
	 * Set the value of a bunch of attributes
	 *
	 * @param array $attributes An array of attributes with keys as names
	 * @return Node_Interface   Reference to the same object
	 * @suppress PhanTypeMismatchReturn
	 */
	public function set_attributes( array $attributes = array() ) : Node_Interface {
		foreach ( $attributes as $key => $val ) {
			$this->set_attribute( $key, $val );
		}
		return $this;
	}

	/**
	 * Get the value of a given attribute
	 *
	 * @param  string $attr The name of the attribute
	 * @return string       The value of the attribute. Non-scalar values are json_encoded
	 */
	public function get_attribute( string $attr ) : string {
		if ( isset( $this->attributes[ $attr ] ) ) {
			return is_string( $this->attributes[ $attr ] ) ? $this->attributes[ $attr ] : json_encode( $this->attributes[ $attr ] );
		}
		return '';
	}

	/**
	 * Get the value of all defined attributes
	 *
	 * @return array Value of all attributes, indexed by attribute name
	 */
	public function get_attributes() : array {
		return $this->attributes;
	}

	/**
	 * Get the value of the "class" attribute as an array
	 *
	 * @return array "class" attribute value as an array
	 */
	public function get_class_list() : array {
		$classes = $this->get_attribute( 'class' );
		$classes = explode( ' ', $classes );
		$classes = array_unique( $classes );
		return $classes;
	}

	/**
	 * Get the value of the "class" attribute as a string
	 *
	 * @return string The complete value of "class" as an string (without duplicates)
	 */
	public function get_class_name() : string {
		return implode( ' ', $this->get_class_list() );
	}

	/**
	 * Append a new name to the class attribute
	 *
	 * @param  string $class The new class name
	 * @return Node_Interface        Reference to the same object
	 * @suppress PhanTypeMismatchReturn
	 */
	public function add_class( string $class ) : Node_Interface {
		$classes   = $this->get_class_list();
		$classes[] = $class;
		$this->set_attribute( 'class', implode( ' ', $classes ) );
		return $this;
	}

	/**
	 * Remove a class name from the element
	 *
	 * @param  string $class The class name to be removed
	 * @return Node_Interface        Reference to the same object
	 * @suppress PhanTypeMismatchReturn
	 */
	public function remove_class( string $class ) : Node_Interface {
		$classes = $this->get_class_list();
		$without = Arrays::without( $classes, $class );
		$this->set_attribute( 'class', implode( ' ', $without ) );
		return $this;
	}

	/**
	 * Get the value of the "id" attribute
	 *
	 * @return string The value for the id attribute
	 */
	public function get_id() : string {
		return $this->get_attribute( 'id' );
	}

	/**
	 * Output all the element's attributes.
	 *
	 * @uses esc_attr()
	 * @return string Element attributes
	 */
	protected function render_attributes() : string {
		$out = '';
		foreach ( $this->get_attributes() as $key => $val ) {
			if ( ! is_string( $val ) ) {
				$val = json_encode( $val );
			}
			$out .= ' ' . $key . '="' . esc_attr( trim( $val ) ) . '"';
		}
		return $out;
	}

	/**
	 * Loop the element properties and check if they're allowed HTML attributes
	 *
	 * @suppress PhanUndeclaredMethod
	 */
	protected function collect_attributes() {
		if ( is_callable( [ $this, 'get_properties' ] ) ) {
			$element_attributes = array_merge( static::get_global_attributes(), static::get_element_attributes() );
			foreach ( $this->get_properties() as $key => $val ) {
				if ( in_array( $key, $element_attributes ) ) {
					$this->set_attribute( $key, $val );
					continue;
				}
				// also check for compound attributes, such as data-src, aria-required, etc.
				foreach ( static::get_global_attribute_pattern_prefixes() as $prefix ) {
					if ( preg_match( '/' . $prefix . '[-]\w+/', $key ) ) {
						$this->set_attribute( $key, $val );
						continue;
					}
				}
			}
		}
	}
}
