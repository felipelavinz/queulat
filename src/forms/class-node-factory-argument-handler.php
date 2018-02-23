<?php

namespace Queulat\Forms;

/**
 * Describes how to handle an argument on the Node_Factory
 */
class Node_Factory_Argument_Handler {
	private $argument  = '';
	private $method    = '';
	private $call_type = Node_Factory::CALL_TYPE_DEFAULT;

	/**
	 * Build a new handler
	 *
	 * @param string $argument  The name of the argument key to be handled
	 * @param string $method    The name of the method that will be called on the object
	 * @param string $call_type How to handle array values on the argument
	 */
	public function __construct( string $argument = '', string $method = '', string $call_type = Node_Factory::CALL_TYPE_DEFAULT ) {
		if ( ! empty( $argument ) ) {
			$this->set_argument( $argument );
		}
		if ( ! empty( $method ) ) {
			$this->set_method( $method );
		}
		if ( ! empty( $call_type ) ) {
			$this->set_call_type( $call_type );
		}
	}

	/**
	 * Set the name of the argument that this class defines how to handle
	 *
	 * @param string $argument The argument key used on the Factory
	 * @return Node_Factory_Argument_Handler Reference to this
	 */
	public function set_argument( string $argument ) : Node_Factory_Argument_Handler {
		$this->argument = $argument;
		return $this;
	}

	/**
	 * Set the name of the method that will be called on the object
	 *
	 * @param string $method Name of the method
	 * @return Node_Factory_Argument_Handler
	 */
	public function set_method( string $method ) : Node_Factory_Argument_Handler {
		$this->method = $method;
		return $this;
	}

	/**
	 * Define how to handle array values on the argument.
	 *
	 * @param string $call_type The type of call
	 * @see    Node_Factory
	 * @return Node_Factory_Argument_Handler
	 */
	public function set_call_type( string $call_type ) : Node_Factory_Argument_Handler {
		$this->call_type = $call_type;
		return $this;
	}

	/**
	 * Check if the property is set on this object
	 *
	 * @return bool
	 */
	public function __isset( $key ) {
		return array_key_exists( $key, get_object_vars( $this ) );
	}

	/**
	 * Get the value of protected/private vars
	 *
	 * @param string $key Name of the property
	 * @return mixed
	 */
	public function __get( $key ) {
		return isset( $this->{$key} ) ? $this->{$key} : null;
	}
}
