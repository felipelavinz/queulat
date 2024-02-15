<?php

namespace Queulat;

trait Singleton {
	protected static $instance = null;

	/**
	 * Return instantiated class
	 * @return static
	 */
	public static function get_instance() {
		if ( is_null( static::$instance ) ) {
			$class            = get_called_class();
			static::$instance = new $class();
		}
		return static::$instance;
	}
	public function __construct() {
	}
}
