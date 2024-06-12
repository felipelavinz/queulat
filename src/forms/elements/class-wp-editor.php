<?php

namespace Queulat\Forms\Element;

use Queulat\Forms\Form_Component;

class WP_Editor extends Form_Component {
	public function get_default_properties() {
		return [
			'wpautop'       => true,
			'media_buttons' => true,
			'teeny'         => false,
			'dfw'           => false,
			'tinymce'       => true,
			'quicktags'     => true,
		];
	}

	private function sanitize_id() {
		$id = $this->get_id() . $this->get_name();
		$id = preg_replace( '/[^a-z]/', '', $id );
		return strtolower( $id );
	}

	/**
	 * Output WordPress tinymce editor.
	 *
	 * If the field is shown on a post type that uses the block editor, the field will be shown as a preview with an edit button.
	 *
	 * @return string Tinymce editor
	 */
	public function __toString() {
		if ( ! is_admin() ) {
			return $this->good_old_editor();
		}
		$current_screen = get_current_screen();
		if ( 'post' !== $current_screen->base ) {
			return $this->good_old_editor();
		}
		$post_id = filter_input( INPUT_GET, 'post', FILTER_SANITIZE_NUMBER_INT );
		if ( use_block_editor_for_post_type( $current_screen->post_type ) || use_block_editor_for_post( $post_id ) ) {
			return $this->block_editor_compat();
		}
		return $this->good_old_editor();
	}

	/**
	 * Direct output of the tinymce editor.
	 *
	 * @return string
	 */
	private function good_old_editor() {
		ob_start();
		$args = wp_parse_args( $this->get_properties(), $this->get_default_properties() );
		if ( empty( $args['textarea_name'] ) ) {
			$args['textarea_name'] = $this->get_name();
		}
		wp_editor( $this->get_value(), $this->sanitize_id(), $args );
		return ob_get_clean();
	}

	/**
	 * Output a preview of the content with an edit button.
	 *
	 * The editor is actually inserted and initialized on the page, but hidden with CSS. For some
	 * reason, the block editor interferes with the initialization of the editor, so the user must
	 * click on the edit button to activate the desired editor.
	 *
	 * @return string|false
	 */
	private function block_editor_compat() {
		ob_start();
		$args = wp_parse_args( $this->get_properties(), $this->get_default_properties() );
		if ( empty( $args['textarea_name'] ) ) {
			$args['textarea_name'] = $this->get_name();
		}
		echo '<div class="queulat-wp-editor">';
			echo '<div class="queulat-wp-editor__preview">';
				echo '<div class="queulat-wp-editor__preview__content" style="border:1px solid #8c8f94;border-radius:4px;padding:6px 8px;line-height:1.42857143;margin-bottom:8px;min-height:30px;max-width:50rem">';
					echo $this->get_value();
				echo '</div>';
				echo '<button class="queulat-wp-editor__edit-button button button-secondary" type="button">';
					echo '<span class="dashicons dashicons-edit" style="vertical-align:middle"></span> ';
					echo __( 'Edit', 'queulat' );
				echo '</button>';
			echo '</div>';
			echo '<div class="queulat-wp-editor__editor hidden">';
				wp_editor( $this->get_value(), $this->sanitize_id(), $args );
			echo '</div>';
		echo '</div>';
		wp_enqueue_script( 'queulat-element-wp-editor', plugins_url( 'js/element-wp-editor.js', __FILE__ ), array( 'jquery', 'underscore' ) );
		return ob_get_clean();
	}

}
