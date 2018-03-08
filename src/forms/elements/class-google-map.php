<?php

namespace Queulat\Forms\Element;

use Queulat\Forms\Element\Div;
use Queulat\Forms\Node_Factory;
use Queulat\Forms\Form_Component;
use Queulat\Forms\Element\Input_Text;
use Queulat\Forms\Element\Input_Hidden;

class Google_Map extends Form_Component {
	/**
	 * @inheritDoc
	 */
	public function __toString() : string {
		$name  = $this->get_name();
		$value = $this->get_value();
		$container = Node_Factory::make(
			Div::class, [
				'attributes' => [
					'class' => 'queulat-gmapsearch'
				],
				'children' => [
					Node_Factory::make(
						Input_Text::class, [
							'name'       => "{$name}[address]",
							'value'      => $value->address ?? '',
							'attributes' => [
								'class'  => 'regular-text gmapsearch__address',
								'placeholder' => __('Search for a location or address', 'queulat')
							],
						]
					),
					Node_Factory::make(
						Div::class, [
							'attributes' => [
								'class' => 'gmapsearch__canvas',
								'style' => 'min-height: 360px'
							]
						]
					),
					Node_Factory::make(
						Input_Hidden::class, [
							'name' => "{$name}[lat]",
							'value' => $value->lat ?? '',
							'attributes' => [ 'class' => 'gmapsearch__lat' ]
						]
					),
					Node_Factory::make(
						Input_Hidden::class, [
							'name' => "{$name}[lng]",
							'value' => $value->lng ?? '',
							'attributes' => [ 'class' => 'gmapsearch__lng' ]
						]
					),
					Node_Factory::make(
						Input_Hidden::class, [
							'name' => "{$name}[zoom]",
							'value' => $value->zoom ?? '',
							'attributes' => [ 'class' => 'gmapsearch__zoom' ]
						]
					),
					Node_Factory::make(
						Input_Hidden::class, [
							'name' => "{$name}[components]",
							'value' => $value->components ?? '',
							'attributes' => [ 'class' => 'gmapsearch__components' ]
						]
					)
				]
			]
		);

		$this->enqueue_assets();

		return (string) $container;
	}

	/**
	 * Enqueue scripts required for the component
	 *
	 * @return void
	 */
	public function enqueue_assets() {
		static::enqueue_js_api();
		wp_enqueue_script( 'queulat__google-map', queulat_url( 'src/forms/elements/js/element-google-map.js' ), ['jquery'], '', true );
	}

	public static function enqueue_js_api() {
		$google_maps_url = add_query_arg( [
			'v' => 3,
			'hl' => get_option('WPLANG') ? current( explode('_', get_option('WPLANG') ) ) : 'en',
			'key' => static::get_api_key(),
			'libraries' => 'places'
		], 'https://maps.googleapis.com/maps/api/js' );
		wp_enqueue_script( 'google-maps-api', $google_maps_url, [], null );
	}

	/**
	 * Get the API Key for using the component
	 *
	 * @return string
	 */
	public static function get_api_key() : string {
		if ( defined('GOOGLE_MAPS_API_KEY') ) {
			return GOOGLE_MAPS_API_KEY;
		}
		if ( env('GOOGLE_MAPS_API_KEY') ) {
			return env('GOOGLE_MAPS_API_KEY');
		}
		$api_key = apply_filters('queulat/forms/element/google-map__api_key', '');
		return $api_key;
	}
}