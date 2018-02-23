<?php
/**
 * Plugin Name: Queulat
 * Plugin URI: https://www.yukei.net/queulat
 * Description: Developers toolset for WordPress
 * Version: 0.1.0
 * Author: Felipe Lavín
 * Author URI: https://www.yukei.net
 * License: GPL-3.0
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 * Text Domain: queulat
 * Domain Path: src/languages
 */

if ( is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/vendor/autoload.php';
} elseif ( is_readable( __DIR__ . '/queulat/vendor/autoload.php' ) ) {
	require_once __DIR__ . '/queulat/vendor/autoload.php';
}

( new Queulat\Bootstrap )->init();