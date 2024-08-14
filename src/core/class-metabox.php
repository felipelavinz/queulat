<?php

namespace Queulat;

use Queulat\Helpers\Arrays;
use Queulat\Forms\Element\Fieldset;
use Queulat\Forms\Element\WP_Nonce;
use Queulat\Forms\Form_Node_Interface;

abstract class Metabox {

	/**
	 * Hold the metabox ID
	 *
	 * @var string
	 */
	protected $id;

	/**
	 * Hold the (localized) metabox title
	 *
	 * @var string
	 */
	protected $title = '';

	/**
	 * Hold the metabox context (normal, advanced or side)
	 *
	 * @var string
	 */
	protected $context = 'normal';

	/**
	 * Hold the metabox priority (high, low or default)
	 *
	 * @var string
	 */
	protected $priority = 'default';

	/**
	 * Hold the post type(s) the metabox was registered for
	 *
	 * @var string
	 */
	protected $post_type = '';

	/**
	 * Hold the callback args
	 *
	 * @var array
	 */
	protected $callback_args = array();

	/**
	 * Set some default args for the metabox registration
	 *
	 * @var array
	 */
	protected static $default_args = array(
		'context'  => 'normal',
		'priority' => 'default',
	);

	/**
	 * Hold metabox params
	 *
	 * @var array
	 */
	protected $args = [];

	/**
	 * Create a new metabox
	 *
	 * @param string $title Localized title of the metabox
	 * @param string $post_type The post type where the metabox will be shown
	 * @param array  $args Metabox arguments
	 */
	public function __construct( $id = '', $title = '', $post_type = '', array $args = array() ) {
		if ( $id ) {
			$this->set_id( $id );
		}
		if ( $title ) {
			$this->title = $title;
		}
		if ( $post_type ) {
			$this->post_type = $post_type;
		}
		$this->set_args( $args );
		$this->init();
	}

	public function set_id( $id ) {
		$this->id = sanitize_key( $id );
	}
	public function get_id() {
		return $this->id;
	}
	public function set_title( $title ) {
		$this->title = sanitize_text_field( $title );
	}
	public function get_title() {
		return $this->title;
	}
	public function set_post_type( $post_type ) {
		$this->post_type = $post_type;
	}
	public function get_post_type() {
		return $this->post_type;
	}
	public function set_args( array $args ) {
		$this->args = wp_parse_args( $args, static::$default_args );
	}
	public function get_args() {
		return $this->args;
	}

	/**
	 * Get form fields for this metabox
	 *
	 * @return array An array of form fields
	 */
	abstract public function get_fields() : array;

	/**
	 * Sanitize metabox data
	 *
	 * @param  array $data  Submitted form data
	 * @return array        Sanitized data
	 */
	abstract public function sanitize_data( array $data ) : array;

	/**
	 * Set-up common metabox actions
	 */
	public function init() {
		// add meta box
		add_action( 'add_meta_boxes_' . $this->get_post_type(), array( $this, 'add_metabox' ) );
		// save
		add_action( 'save_post', array( $this, 'save_metabox' ), 10, 2 );
		// allow file uploads
		add_action( 'post_edit_form_tag', array( $this, 'allow_form_uploads' ) );
	}

	/**
	 * Do the actual metabox registration for the given post-type
	 */
	final public function add_metabox() {
		$do_add = apply_filters( 'add_meta_box_'. $this->get_id(), true, $this );
		if ( ! $do_add ) {
			return false;
		}
		add_meta_box( $this->get_id(), $this->get_title(), array( $this, 'content_callback' ), $this->get_post_type(), $this->args['context'], $this->args['priority'] );
	}

	/**
	 * Enable file uploads on the #post submission form
	 *
	 * @todo Perhaps detect if it's necessary (go through metabox elements to check if there's a file upload?)
	 */
	final public function allow_form_uploads() {
		static $allowed;
		$allowed = false;
		if ( ! $allowed ) {
			echo ' enctype="multipart/form-data"';
		}
		$allowed = true;
	}

	/**
	 * Create and echo the metabox content
	 */
	final public function content_callback() {
		global $post;
		// create "form"... wich are not actually forms, since they are part
		// of the greater "post" form
		$form = new Forms\Metabox_Form( array( 'id' => $this->id . '-form' ) );
		$args = $this->get_args();
		if ( isset( $args['context'] ) && 'side' == $args['context'] ) {
			$form->set_view( 'Queulat\Forms\View\WP_Side' );
		}
		$fields = $this->get_fields();
		foreach ( $fields as $field ) {
			$this->prepare_field( $field );

			/**
			 * Enable further customization on a metabox form field
			 *
			 * @param Queulat\Forms\Element_Interface $field The field instance (passed by reference)
			 * @param Queulat\Forms\Element\Form $form The form instance (passed by reference)
			 * @param Queulat\Metabox $this The metabox instance (passed by reference)
			 */
			do_action_ref_array( 'queulat_metabox_field', array( &$field, &$form, &$this ) );

			$form->append_child( $field );
		}

		// add a nonce for security...
		$nonce = new WP_Nonce();
		$nonce->set_property( 'action', $this->get_id() . '_metabox' );
		$nonce->set_property( 'name', $this->get_id() . '_nonce' );
		$form->append_child( $nonce );

		echo $form;
	}

	/**
	 * Set field value from post meta and prefix name with form id
	 *
	 * @param Element_Interface $field
	 */
	private function prepare_field( $field ) {
		global $post;
		if ( $field instanceof Fieldset ) {
			foreach ( $field->get_children() as $child ) {
				$this->prepare_field( $child );
			}
		} elseif ( $field instanceof Form_Node_Interface ) {
			$value = get_post_meta( $post->ID, $this->get_field_key( $field ), false );
			switch ( count( $value ) ) {
				case 0:
					// no value, skip
					break;
				case 1:
					// if it's a single value, set as string
					$field->set_value( current( $value ) );
					break;
				default:
					// ... otherwhise, as array
					$field->set_value( $value );
					break;
			}
			$field->set_name( $this->get_id() . '_metabox[' . $field->get_name() . ']' );
		}
	}
	public function get_field_key( $field ) {
		return $this->get_id() . '_' . $field->get_name();
	}

	/**
	 * Save metabox data. Will add/update/delete data accordingly
	 *
	 * @param int                   $post_id The post ID
	 * @param object WP_Post object
	 * @throws \Exception If user doesn't have permission to save data
	 * @uses apply_filters() Calls 'filter_'. $this->get_id() .'_metabox_data' to filter data before saving
	 * @uses do_action() Calls $this->get_id() .'_metabox_data_update' before data is saved
	 * @uses do_action() Calls $this->get_id() .'_metabox_data_updated' after data is saved
	 */
	final public function save_metabox( $post_id, \WP_Post $post ) {
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return;
		}

		if ( $this->get_post_type() !== $post->post_type ) {
			return;
		}

		try {
			$this->check_permissions( $post_id );
		} catch ( \Exception $e ) {
			wp_die( $e->getMessage() );
		}

		// no data sent
		if ( ! isset( $_POST[ $this->get_id() . '_metabox' ] ) && ! isset( $_POST[ $this->id . '_nonce' ] ) ) {
			return;
		}

		$data = (array) $_POST[ $this->get_id() . '_metabox' ];
		$data = Arrays::filter_recursive( $data );

		// no need for slashes; WordPress will take care of sanitizing when using "add/update_post_meta"
		$data = stripslashes_deep( $data );

		$data = $this->sanitize_data( $data );

		// handle file uploads
		if ( ! empty( $_FILES[ $this->id . '-form' ] ) ) {
			try {
				$uploads = $this->handle_uploads( $post_id );
				$data    = array_merge( $data, $uploads );
			} catch ( \Exception $e ) {
				wp_die( $e->getMessage() );
			}
		}

		// and now, you may filter the metabox data to do some
		// sanitizing and formatting
		$data = apply_filters( 'filter_' . $this->get_id() . '_metabox_data', $data, $this, $post );

		// hook into this action if you need to do something before metadata is saved
		do_action( $this->get_id() . '_metabox_data_update', $data, $post_id, $post, $this );

		foreach ( $this->get_fields() as $element ) {
			if ( $element instanceof Fieldset ) {
				foreach ( $element->get_children() as $child ) {
					if ( is_callable( [ $child, 'get_name' ] ) ) {
						$this->update_post_meta( $post_id, $child->get_name(), $data );
					}
				}
			} else {
				if ( is_callable( [ $element, 'get_name' ] ) ) {
					$this->update_post_meta( $post_id, $element->get_name(), $data );
				}
			}
		}

		// hook into this action if you need to do something after metadata was saved
		do_action( $this->get_id() . '_metabox_data_updated', $data, $post_id, $post, $this );
	}

	/**
	 * Add, update or remove a post meta data
	 *
	 * @param  int    $post_id The post ID
	 * @param  string $key The key where the data it's on the submitted info
	 * @param  array  $data The array of submitted info
	 */
	private function update_post_meta( $post_id, $key, $data ) {
		if ( isset( $data[ $key ] ) ) {
			if ( is_array( $data[ $key ] ) ) {
				delete_post_meta( $post_id, $this->id . '_' . $key );
				foreach ( $data[ $key ] as $val ) {
					add_post_meta( $post_id, $this->id . '_' . $key, $val );
				}
			} else {
				update_post_meta( $post_id, $this->id . '_' . $key, $data[ $key ] );
			}
		} else {
			// if data it's defined, but no data is sent, try to delete the given key
			// this will take care of checkboxes and such
			delete_post_meta( $post_id, $this->id . '_' . $key );
		}
	}

	/**
	 * Handle file uploads and save them as attachments
	 *
	 * @param int $post_id The post ID
	 * @throws \Exception On upload error
	 * @return array A collection of successful uploads, with $_FILES name as keys and attachment IDs as values
	 */
	private function handle_uploads( $post_id ) {
		$files = array();

		foreach ( (array) $_FILES[ $this->id . '-form' ] as $prop => $file ) {
			foreach ( $file as $key => $val ) {
				$files[ $key ][ $prop ] = $val;
			}
		}

		// check if no file was uploaded
		foreach ( $files as $name => $upload ) {
			if ( 4 === $upload['error'] ) {
				unset( $files[ $name ] );
			}
		}

		if ( empty( $files ) ) {
			return array();
		}

		$uploads = array();

		foreach ( $files as $name => $file ) {
			$date = date( 'Y/m' );

			$upload = wp_handle_upload( $file, array( 'test_form' => false ), $date );

			// errors
			if ( isset( $upload['error'] ) ) {
				throw new \Exception( $upload['error'] );
			}
			if ( ! isset( $upload['file'] ) ) {
				throw new \Exception( sprintf( __( 'Error uploading file %s', 'queulat' ), $file['name'] ) );
			}

			$current_user_id = wp_get_current_user()->ID;
			$wp_filetype     = wp_check_filetype( basename( $upload['file'] ) );

			$attachment = array(
				'post_mime_type' => $wp_filetype['type'],
				'guid'           => $upload['url'],
				'post_title'     => preg_replace( '/\.[^.]+$/', '', basename( $upload['file'] ) ),
				'post_content'   => '',
				'post_status'    => 'inherit',
				'post_author'    => $current_user_id,
				'post_parent'    => $post_id,
			);

			$attach_id        = wp_insert_attachment( $attachment, $upload['file'], $post_id );
			$uploads[ $name ] = $attach_id;

			require_once ABSPATH . 'wp-admin/includes/image.php';

			$attach_data = wp_generate_attachment_metadata( $attach_id, $upload['file'] );
			wp_update_attachment_metadata( $attach_id, $attach_data );

		}

		return $uploads;
	}

	/**
	 * Check if the current user has permission to save data on the given post
	 *
	 * @param int $postid The current Post ID
	 * @throws \Exception If user doesn't have permissions on this entry
	 * @return bool True if users passes checks
	 */
	private function check_permissions( int $postid ) : bool {
		// nonce it's not present when not saving
		if ( ! isset( $_POST[ $this->id . '_nonce' ] ) ) {
			return false;
		}

		// verify nonce
		if ( ! wp_verify_nonce( $_POST[ $this->id . '_nonce' ], $this->id . '_metabox' ) ) {
			throw new \Exception( sprintf( __( 'It seems you\'re not allowed to save data on %s.', 'queulat' ), $this->title ) );
		}
		// get permissions
		$post_type       = get_post_type_object( $this->get_post_type() );
		$edit_capability = $post_type->cap->edit_post;
		if ( ! current_user_can( $edit_capability, $postid ) ) {
			throw new \Exception( __( 'You are not authorized to edit this content', 'queulat' ) );
		}
		return true;
	}
}
