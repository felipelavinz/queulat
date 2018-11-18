<?php

namespace Queulat\Forms\Element;

use Queulat\Forms\Node_Factory;
use Queulat\Forms\Form_Component;
use Queulat\Forms\Form_Control_Trait;

class Recaptcha extends Form_Component {
	use Form_Control_Trait;
	public function __toString() {
		$div = Node_Factory::make(
			Div::class, [
				'attributes' => [
					'class'        => 'g-recaptcha',
					'data-sitekey' => static::get_site_key(),
				],
			]
		);
		wp_enqueue_script( 'recaptcha', 'https://www.google.com/recaptcha/api.js', [], null, true );
		return $div;
	}
	public static function get_site_key() : string {
		if ( defined( 'RECAPTCHA_SITE_KEY' ) ) {
			return RECAPTCHA_SITE_KEY;
		}
		if ( function_exists( 'env' ) && env( 'RECAPTCHA_SITE_KEY' ) ) {
			return env( 'RECAPTCHA_SITE_KEY' );
		}
		$site_key = apply_filters( 'queulat/forms/element/recaptcha__site-key', '' );
		return $site_key;
	}
	public static function get_site_secret() : string {
		if ( defined( 'RECAPTCHA_SITE_SECRET' ) ) {
			return RECAPTCHA_SITE_SECRET;
		}
		if ( function_exists( 'env' ) && env( 'RECAPTCHA_SITE_SECRET' ) ) {
			return env( 'RECAPTCHA_SITE_SECRET' );
		}
		$site_secret = apply_filters( 'queulat/forms/element/recaptcha__site-secret', '' );
		return $site_secret;
	}
}
