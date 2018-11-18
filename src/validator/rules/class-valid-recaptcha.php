<?php

namespace Queulat\Validator;

use Queulat\Forms\Element\Recaptcha;


class Valid_Recaptcha implements Validator_Interface {
	public function is_valid( $value ) : bool {
		$request = wp_remote_post(
			'https://www.google.com/recaptcha/api/siteverify', [
				'body' => [
					'secret'   => Recaptcha::get_site_secret(),
					'response' => $value,
				],
			]
		);
		if ( wp_remote_retrieve_response_code( $request ) != 200 ) {
			return false;
		}
		$response = json_decode( wp_remote_retrieve_body( $request ) );
		return (bool) $response->success;
	}
	public function get_message() : string {
		return '';
	}
}
