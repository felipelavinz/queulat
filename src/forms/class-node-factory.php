<?php

namespace Queulat\Forms;

use Underscore\Types\Arrays;

/**
 * Node Factory creates any kind of form element or component
 */
class Node_Factory {

	/**
	 * Define the default way that array arguments should be handed off to the
	 * the corresponding method for any given argument
	 */
	const CALL_TYPE_DEFAULT = 'CALL_VALUE';

	/**
	 * Pass all arguments as a single array to the hanler
	 */
	const CALL_TYPE_VALUE = 'CALL_VALUE';

	/**
	 * Pass arguments as individual parameters to the handler
	 */
	const CALL_TYPE_ARRAY = 'CALL_ARRAY';

	/**
	 * For each item in the argument, pass its key and value as parameters to
	 * the handler
	 */
	const CALL_TYPE_KEY_VALUE = 'CALL_KEY_VALUE';

	/**
	 * For each item in the argument, use the value as parameter for the handler
	 */
	const CALL_TYPE_VALUE_ITEMS = 'CALL_VALUE_ITEMS';

	/**
	 * Hold a set of registered arguments handled by the factory
	 *
	 * Each element it's indexed by argument name and has a Node_Factory_Argument_Handler
	 * as value.
	 *
	 * @var array
	 */
	private static $registered_arguments = [];

	/**
	 * Allow using the factory as an object. Forward method calls to static methods
	 *
	 * @param string $name     The invoked method
	 * @param array  $arguments Method arguments
	 * @return void
	 * @throws \BadFunctionCallException When trying to call an undefined method
	 */
	public function __call( $name, $arguments ) {
		if ( method_exists( __CLASS__, $name ) ) {
			call_user_func_array( [ __CLASS__, $name ], $arguments );
		} else {
			throw new \BadFunctionCallException( sprintf( _x( 'Call to undefined method %s', 'node factory exception', 'queulat' ), __CLASS__ . "::{$name}()" ) );
		}
	}

	/**
	 * Create a new form element or component
	 *
	 * @param string $element_name Fully qualified name for the object class
	 * @param array  $args          An specification of arguments used to build the object
	 * @return Node_Interface      An instantiated object
	 */
	public static function make( string $element_name, array $args = [] ) : Node_Interface {
		if ( ! class_exists( $element_name ) ) {
			throw new \LogicException( sprintf( _x( "The '%s' element doesn't exists", 'node factory exception', 'queulat' ), $element_name ) );
		}
		$obj  = new $element_name();
		$args = Arrays::reverseFlatten( $args );
		static::configure( $obj, $args );
		return $obj;
	}

	/**
	 * Get the list of registered arguments and their handlers
	 *
	 * @return array
	 */
	public static function get_registered_arguments() {
		return static::$registered_arguments;
	}

	/**
	 * Register a new argument hander
	 *
	 * @param Node_Factory_Argument_Handler $handler
	 * @return void
	 */
	public static function register_argument( Node_Factory_Argument_Handler $handler ) {
		static::$registered_arguments[ $handler->argument ] = $handler;
	}

	/**
	 * Unregister an argument handler
	 *
	 * @param string $argument The key of the argument to unregister
	 * @return void
	 */
	public static function unregister_argument( string $argument ) {
		unset( static::$registered_arguments[ $argument ] );
	}

	/**
	 * Configure an object instance
	 *
	 * @param Node_Interface $obj The element to be configured
	 * @param array          $args         Builiding specs
	 * @return Node_Interface     The built object
	 */
	public static function configure( Node_Interface $obj, array $args = [] ) : Node_Interface {
		foreach ( static::get_registered_arguments() as $argument => $handler ) {
			if ( ! isset( $args[ $argument ] ) ) {
				continue;
			}
			// check if the object implements the required method
			if ( is_callable( [ $obj, $handler->method ] ) ) {
				if ( is_array( $args[ $argument ] ) ) {
					// check if the arguments should be given as distinct parametedrs to the method,
					// use their keys as arguments or just use the value
					$call_type = $handler->call_type ?? static::CALL_TYPE_DEFAULT;
					switch ( $call_type ) {
						case static::CALL_TYPE_ARRAY:
							call_user_func_array( [ $obj, $handler->method ], $args[ $argument ] );
							break;
						case static::CALL_TYPE_KEY_VALUE:
							foreach ( $args[ $argument ] as $key => $val ) {
								$obj->{$handler->method}( $key, $val );
							}
							break;
						case static::CALL_TYPE_VALUE_ITEMS:
							array_walk( $args[ $argument ], [ $obj, $handler->method ] );
							break;
						case static::CALL_TYPE_VALUE:
							$obj->{ $handler->method }( $args[ $argument ] );
							break;
					}
				} else {
					$obj->{$handler->method}( $args[ $argument ] );
				}
			}
		}
		return $obj;
	}
}
