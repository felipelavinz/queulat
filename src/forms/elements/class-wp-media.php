<?php

namespace Queulat\Forms\Element;

use Queulat\Forms\Form_Component;
use Queulat\Forms\Node_Factory;

class WP_Media extends Form_Component {

	public static function get_element_attributes() : array {
		return [
			'class',
		];
	}

	public function get_defaults() {
		return [
			'title'    => $this->get_label(),
			'multiple' => false,
			'button'   => [
				'text' => _x( 'Select file', 'WP_Media button text', 'queulat' ),
			],
		];
	}

	public function get_upload_button_text() {
		return _x( 'Upload or select an existing file', 'WP_Media button text', 'queulat' );
	}

	public function get_remove_button_text() {
		return _x( 'Remove file', 'WP_Media button text', 'queulat' );
	}

	public function get_replace_button_text() {
		return _x( 'Replace file', 'WP_Media button text', 'queulat' );
	}

	public function __toString() {
		if ( $this->get_property( 'test_scripts' ) && ! did_action( 'wp_enqueue_media' ) ) {
			return (string) Node_Factory::make(
				Div::class,
				[
					'attributes'   => [
						'class' => 'error inline',
					],
					'text_content' => '<p>' . __( 'You need to call wp_enqueue_media() on the admin_enqueue_scripts hook', 'queulat' ) . '</p>',
				]
			);
		}

		// add the "js-queulat-wp-media" class for the js plugin.
		$this->add_class( 'js-queulat-wp-media' );

		// get custom instance attributes.
		// you can pass them on an "instance" property or as the "data-wpmedia" attribute.
		$instance = [];
		if ( $this->get_property( 'instance' ) ) {
			$instance = $this->get_property( 'instance' );
		} elseif ( $this->has_attribute( 'data-wpmedia' ) ) {
			$instance = $this->get_attribute( 'data-wpmedia' );
		}

		$this->set_attribute( 'data-wpmedia-args', wp_parse_args( $instance, $this->get_defaults() ) );

		$value = $this->get_value();
		if ( $value ) {
			$items = array_map( 'wp_prepare_attachment_for_js', (array) $value );
			$this->set_attribute( 'data-wpmedia-value', $items );
		}

		$component_children = [
			Node_Factory::make(
				Div::class,
				[
					'attributes' => [
						'class' => 'thumb-receiver queulat-wpmedia-receiver queulat-wpmedia-sortable',
					],
				]
			),
			Node_Factory::make(
				Div::class,
				[
					'attributes' => [
						'class' => 'clear',
					],
					'children'   => [
						Node_Factory::make(
							Button::class,
							[
								'attributes'   => [
									'type'               => 'button',
									'class'              => 'button queulat-wpmedia-upload',
									'data-label-add'     => esc_attr( $this->get_upload_button_text() ),
									'data-label-replace' => esc_attr( $this->get_replace_button_text() ),
								],
								'text_content' => empty( $value ) ? $this->get_upload_button_text() : $this->get_replace_button_text(),
							]
						),
					],
				]
			),
			Node_Factory::make(
				Div::class,
				[
					'text_content' => $this->get_item_template(),
				]
			),
		];

		$component = Node_Factory::make(
			Div::class,
			[
				'attributes' => $this->get_attributes(),
				'children'   => $component_children,
			]
		);

		wp_enqueue_script( 'queulat-element-wp-media', plugins_url( 'js/element-wp-media.js', __FILE__ ), array( 'jquery', 'jquery-ui-sortable', 'underscore' ) );
		return (string) $component;
	}
	public function get_item_template() {
		$name       = $this->get_name() . '[]';
		$remove_lbl = esc_attr( $this->get_remove_button_text() );
		$out        = <<<EOL
<script type="text/html" class="tmpl-wpmedia-item">
	<div class="sortable queulat-wpmedia-item queulat-wpmedia-sortable-item attachment">
		<div class="attachment-preview type-<%- attributes.type %> subtype-<%- attributes.subtype %> <% if ( ! _.isEmpty( attributes.orientation ) ) { %><%- attributes.orientation %><% } %>">
			<div class="thumbnail">
				<% if ( ! _.isEmpty( attributes.sizes ) ) { %>
				<div class="centered">
					<% if ( ! _.isEmpty( attributes.sizes.thumbnail ) ) { %>
						<img src="<%- attributes.sizes.thumbnail.url %>" alt="<%- attributes.title %>" width="<%- attributes.sizes.thumbnail.width %>" heigt="<%- attributes.sizes.thumbnail.height %>">
					<% } else { %>
						<img src="<%- attributes.sizes.full.url %>" alt="<%- attributes.title %>" width="<%- attributes.sizes.full.width %>" heigt="<%- attributes.sizes.full.height %>">
					<% } %>
				</div>
				<% } else { %>
				<div class="centered">
					<img src="<%- attributes.icon %>" alt="<%- attributes.name %>">
				</div>
				<div class="filename">
					<div class="queulat-wpmedia-item-title"><%- attributes.title %></div>
				</div>
				<% } %>
			</div>
			<button type="button" class="button-link attachment-close media-modal-icon queulat-wpmedia-item-remove"><span class="screen-reader-text">{$remove_lbl}</span></button>
		</div>
		<input type="hidden" class="queulat-wpmedia-value" name="{$name}" value="<%- attributes.id %>">
	</div>
</script>
EOL;
		return $out;
	}
}
