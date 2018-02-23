<?php

namespace Queulat\Forms\Element;

use Queulat\Forms;

class WP_Media extends Forms\Form_Component {

	use Forms\Attributes_Trait, Forms\Properties_Trait;

	public function append_node_child( Forms\Element_Interface $element ) {
		return;
	}

	public function get_children() {
		return array();
	}

	public function get_tag_name() {
		return 'wp-media';
	}

	public function get_default_args() {
		return [
			'title'    => $this->get_label(),
			'multiple' => false,
			'button'   => array(
				'text' => _x( 'Select file', 'WP_Media button text', 'queulat' ),
			),
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
		$out = '';
		if ( $this->get_property( 'test_scripts' ) && ! did_action( 'wp_enqueue_media' ) ) {
			return '<div class="error inline"><p>' . __( 'You need to call wp_enqueue_media() on the admin_enqueue_scripts hook', 'queulat' ) . '</p></div>';
		}

		// add the "gp-wp-media" class for the js plugin
		$this->add_class( 'gp-wp-media' );

		// get custom instance attributes
		// you can pass them on an "instance" property or as the "data-wpmedia" attribute
		$instance = array();
		if ( $this->get_property( 'instance' ) ) {
			$instance = $this->get_property( 'instance' );
		} elseif ( $this->has_attribute( 'data-wpmedia' ) ) {
			$raw_data = $this->get_attribute( 'data-wpmedia' );
			// attribute value should be json-encoded
			$instance = is_string( $raw_data ) ? json_decode( $this->get_attribute( 'data-wpmedia' ) ) : $raw_data;
		}

		$this->set_attribute( 'data-wpmedia-args', wp_parse_args( $instance, $this->get_default_args() ) );

		$value = (array) $this->get_value();
		$items = array_map( 'wp_prepare_attachment_for_js', $value );
		$this->set_attribute( 'data-wpmedia-value', $items );

		$out .= '<div' . $this->render_attributes() . '>';
			// show thumbnail(s) or file icon and upload or replace button
			$out         .= '<div class="thumb-receiver gp-wpmedia-receiver gp-wpmedia-sortable">';
			$out         .= '</div>';
			$out         .= '<div class="clear">';
				$out     .= '<button class="button gp-wpmedia-upload" data-label-add="' . esc_attr( $this->get_upload_button_text() ) . '" data-label-replace="' . esc_attr( $this->get_replace_button_text() ) . '">';
					$out .= empty( $value ) ? $this->get_upload_button_text() : $this->get_replace_button_text();
				$out     .= '</button>';
			$out         .= '</div>';
			$out         .= $this->get_item_template();
		$out             .= '</div>';
		wp_enqueue_script( 'gp-element-wp-media', plugins_url( 'js/element-wp-media.js', __FILE__ ), array( 'jquery', 'jquery-ui-sortable', 'underscore' ) );
		return $out;
	}
	public function get_item_template() {
		$name       = $this->get_name() . '[]';
		$remove_lbl = esc_attr( $this->get_remove_button_text() );
		$out        = <<<EOL
<script type="text/html" class="tmpl-wpmedia-item">
	<div class="sortable gp-wpmedia-item gp-wpmedia-sortable-item attachment">
		<div class="attachment-preview type-<%- attributes.type %> subtype-<%- attributes.subtype %> <% if ( ! _.isEmpty( attributes.orientation ) ) { %><%- attributes.orientation %><% } %>">
			<div class="thumbnail">
				<% if ( ! _.isEmpty( attributes.sizes ) ) { %>
				<div class="centered">
					<img src="<%- attributes.sizes.thumbnail.url %>" alt="<%- attributes.title %>" width="<%- attributes.sizes.thumbnail.width %>" heigt="<%- attributes.sizes.thumbnail.height %>">
				</div>
				<% } else { %>
				<div class="centered">
					<img src="<%- attributes.icon %>" alt="<%- attributes.name %>">
				</div>
				<div class="filename">
					<div class="gp-wpmedia-item-title"><%- attributes.title %></div>
				</div>
				<% } %>
			</div>
			<button type="button" class="button-link attachment-close media-modal-icon"><span class="screen-reader-text">$remove_lbl</span></button>
		</div>
		<input type="hidden" class="gp-wpmedia-value" name="$name" value="<%- attributes.id %>">
	</div>
</script>
EOL;
		return $out;
	}
}
