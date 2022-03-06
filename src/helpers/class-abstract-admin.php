<?php

namespace Queulat\Helpers;

use Queulat\Validator;
use Queulat\Forms\Element\Form;
use Queulat\Forms\Node_Factory;
use Queulat\Forms\Element\Button;
use Queulat\Forms\Element\WP_Nonce;
use Queulat\Forms\Element\Input_Hidden;

/**
 * Abstract class to generate administration pages
 */
abstract class Abstract_Admin {

	/**
	 * Store the administration hook for the (sub)page
	 *
	 * @var string
	 */
	private $admin_hook = '';

	/**
	 * Get the ID of the admin page
	 *
	 * @return string
	 */
	abstract public function get_id() : string;

	/**
	 * Get the title of the admin page
	 *
	 * @return string
	 */
	abstract public function get_title() : string;

	/**
	 * Get the menu title of the page
	 *
	 * @return string
	 */
	abstract public function get_menu_title() : string;

	/**
	 * Get the form elements that compose the admin page
	 *
	 * @return array
	 */
	abstract public function get_form_elements() : array;

	/**
	 * Sanitize form pdata
	 *
	 * @param array $input An array or iterable of form elements/components
	 * @return array
	 */
	abstract public function sanitize_data( $input ) : array;

	/**
	 * Get data validation rules
	 *
	 * @param array $sanitized_data Sanitized input data
	 * @return array An array of validation rules with form element names as keys
	 */
	abstract public function get_validation_rules( array $sanitized_data ) : array;

	/**
	 * Get the admin menu icon. Can be a URL to the icon file or a string
	 * identifying one of WordPress' dashicons
	 *
	 * @link https://developer.wordpress.org/resource/dashicons/
	 * @return string
	 */
	abstract public function get_icon() : string;

	/**
	 * Define the action performed by the form
	 *
	 * @param array $data Validated and sanitized data
	 * @return bool
	 */
	abstract public function process_data( array $data ) : bool;

	/**
	 * The parent page slug. If empty, it will be added as a top level page
	 *
	 * @return string
	 */
	public function get_parent_page() : string {
		return '';
	}

	/**
	 * The user capability required to access the admin page
	 *
	 * @return string
	 */
	public function get_required_capability() : string {
		return 'activate_plugins';
	}

	/**
	 * Get the success redirect URL
	 *
	 * By default, it returns the same origin URL. Further params defined on
	 * static::get_success_url_params() are appended
	 *
	 * @return string Success URL
	 */
	public function get_redirect_url() : string {
		// get the relative path to the WordPress Admin
		$relative_admin_url = str_replace( home_url(), '', admin_url() );
		$redirect_url       = str_replace( $relative_admin_url, '', filter_input( INPUT_POST, '_wp_http_referer', FILTER_SANITIZE_URL ) );
		return $redirect_url;
	}

	/**
	 * Get url parameters for successful form submission
	 *
	 * Returned parameters will be appended to the referer URL to show admin notices.
	 *
	 * @return array
	 */
	public function get_success_url_params() : array {
		return [
			'updated' => 'true',
		];
	}

	/**
	 * The admin page position.
	 * See the codex documentation for more info
	 *
	 * @link https://codex.wordpress.org/register_post_type#Arguments
	 * @return integer
	 */
	public function get_position() : int {
		return 0;
	}

	/**
	 * Initialize admin page actions.
	 *
	 * @internal If you need to override this method, consider calling parent::init()
	 * to still register these actions
	 * @return void
	 */
	public function init() {
		add_action( 'admin_menu', [ $this, 'admin_menu' ] );
		add_action( 'admin_init', [ $this, 'process_form' ] );
	}

	/**
	 * Add the admin page (or sub-page, if get_parent_page it's not empty)
	 *
	 * @return string The admin hook of the registered (sub)page
	 */
	public function admin_menu() : string {
		if ( empty( $this->get_parent_page() ) ) {
			$this->admin_hook = add_menu_page(
				$this->get_title(),
				$this->get_menu_title(),
				$this->get_required_capability(),
				$this->get_id(),
				[ $this, 'admin_page' ],
				$this->get_icon(),
				$this->get_position()
			);
		} else {
			$this->admin_hook = add_submenu_page(
				$this->get_parent_page(),
				$this->get_title(),
				$this->get_menu_title(),
				$this->get_required_capability(),
				$this->get_id(),
				[ $this, 'admin_page' ],
				$this->get_position() ? $this->get_position() : null
			);
		}
		return $this->admin_hook;
	}

	/**
	 * Output the admin (sub)page
	 */
	public function admin_page() {
		$form = new Form();
		$form->set_attribute( 'id', $this->get_id() . '__form' );
		$form->set_attribute( 'method', 'POST' );
		foreach ( $this->get_form_elements() as $element ) {
			$form->append_child( $element );
		}
		$form->append_child(
			Node_Factory::make(
				Input_Hidden::class,
				[
					'name'  => 'action',
					'value' => $this->get_id() . '__submit',
				]
			)
		);
		$form->append_child(
			Node_Factory::make(
				Button::class,
				[
					'attributes'   => [
						'type'  => 'submit',
						'class' => 'button button-primary',
					],
					'text_content' => _x( 'Save', 'admin form', 'gutenpress' ),
				]
			)
		);
		$form->append_child(
			Node_Factory::make(
				WP_Nonce::class,
				[
					'properties' => [
						'action' => "{$this->get_id()}__submit",
						'name'   => "_{$this->get_id()}__submit-nonce",
					],
				]
			)
		);

		echo '<div class="wrap">';
		echo "<h1>{$this->get_title()}</h1>";

		/**
		 * Allows for customization of the search form or printing stuff just before it
		 *
		 * @param Queulat\Forms\Element\Form $form
		 * @param static Instantiated admin
		 */
		do_action( "queulat_abstract_admin_form_{$this->get_id()}", $form, $this );

		echo (string) $form;
		echo '</div>';
	}

	/**
	 * Process the form submission.
	 *
	 * Will sanitize and validate data according to provided rules. If
	 * successful, will redirect to the referer URL with provided parameters.
	 * You can override this method as needed.
	 *
	 * @return void
	 */
	public function process_form() {
		if ( filter_input( INPUT_POST, 'action', FILTER_SANITIZE_STRING ) != $this->get_id() . '__submit' ) {
			return;
		}
		check_admin_referer( "{$this->get_id()}__submit", "_{$this->get_id()}__submit-nonce" );
		$postdata  = stripslashes_deep( $_POST );
		$sanitized = $this->sanitize_data( $postdata );
		$validate  = new Validator( $sanitized, $this->get_validation_rules( $sanitized ) );
		if ( ! $validate->is_valid() ) {
			return;
		}
		if ( $this->process_data( $sanitized ) ) {
			wp_safe_redirect( add_query_arg( $this->get_success_url_params(), $this->get_redirect_url() ), 303 );
			exit;
		}
		return;
	}

}
