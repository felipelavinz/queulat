<?php

namespace Queulat\Generator\Admin;

use Underscore\Types\Strings;
use Queulat\Validator\Value_In;
use Queulat\Forms\Node_Factory;
use Queulat\Validator\Max_Length;
use Queulat\Forms\Element\Select;
use Queulat\Forms\Element\Yes_No;
use Queulat\Validator\Is_Required;
use Queulat\Validator\Value_Not_In;
use Queulat\Helpers\Abstract_Admin;
use Queulat\Forms\Element\Fieldset;
use Queulat\Forms\Element\Textarea;
use Queulat\Forms\Element\Input_Text;
use Queulat\Forms\Element\Input_Radio;
use Queulat\Forms\Element\Input_Checkbox;
use Queulat\Generator\Builder\Custom_Post_Type;
use Queulat\Generator\Builder\Custom_Post_Type_Plugin;

/**
 * Generate an admin page for creating a new Custom Post Type plugin
 */
class CPT_Plugin extends Abstract_Admin {
	public function get_id() : string {
		return 'queulat-cpt-plugin-generator';
	}
	public function get_title() : string {
		return _x( 'Generate Custom Post Type Plugin', 'admin title', 'queulat' );
	}
	public function get_menu_title() : string {
		return _x( 'Generate Custom Post Type Plugin', 'admin menu', 'queulat' );
	}
	public function get_parent_page() : string {
		return 'tools.php';
	}
	public function get_icon() : string {
		return '';
	}
	public function get_form_elements() : iterable {
		return [
			Node_Factory::make(
				Input_Text::class, [
					'label'      => _x( 'Post Type slug', 'cpt generator label', 'queulat' ),
					'name'       => 'slug',
					'properties' => [
						'description' => _x( 'A <strong>singular</strong> slug, used as <code>post_type</code> on new entries. Max. 20 characters, no capital letters or spaces', 'cpt generator', 'queulat' ),
					],
				]
			),
			Node_Factory::make(
				Input_Text::class, [
					'label'      => _x( 'Label', 'cpt generator label', 'queulat' ),
					'name'       => 'label',
					'properties' => [
						'description' => _x( 'A <strong>plural</strong> descriptive name for the post type', 'cpt generator', 'queulat' ),
					],
				]
			),
			Node_Factory::make(
				Textarea::class, [
					'label'      => _x( 'Description', 'cpt generator label', 'queulat' ),
					'name'       => 'description',
					'attributes' => [
						'placeholder' => _x( 'A short descriptive summary of what the post type is', 'cpt generator placeholder', 'queulat' ),
						'rows'        => 5,
					],
				]
			),
			Node_Factory::make(
				Input_Radio::class, [
					'label'   => _x( 'Public', 'cpt generator label', 'queulat' ),
					'name'    => 'public',
					'options' => [
						'1' => __( 'True: Post type is intended for public use. This includes on the front end and in wp-admin', 'queulat' ),
						'0' => __( 'False: Post type is not intended to be used publicly and should generally be unavailable in wp-admin and on the front end unless explicitly planned for elsewhere', 'queulat' ),
					],
				]
			),
			Node_Factory::make(
				Input_Checkbox::class, [
					'label'   => _x( 'Supports', 'cpt generator label', 'queulat' ),
					'name'    => 'supports',
					'options' => Custom_Post_Type::get_supports(),
				]
			),
			Node_Factory::make(
				Yes_No::class, [
					'label' => __( 'Hierarchical', 'queulat' ),
					'name' => 'hierarchical',
					'properties' => [
						'description' => __( "Whether the post type is hierarchical. Allows Parent to be specified. The 'supports' parameter should contain 'page-attributes' to show the parent select box on the editor page", 'queulat' )
					]
				]
			),
			Node_Factory::make(
				Yes_No::class, [
					'label' => __( 'Has archive', 'queulat' ),
					'name' => 'has_archive',
					'properties' => [
						'description' => __( 'Enables post type archives', 'queulat' )
					]
				]
			),
			Node_Factory::make(
				Fieldset::class, [
					'properties' => [
						'label' => __( 'Rewrite', 'queulat' )
					],
					'children' => [
						Node_Factory::make(
							Yes_No::class, [
								'label' => 'Enable',
								'name' => 'rewrite_enable',
							]
						),
						Node_Factory::make(
							Input_Text::class, [
								'label' => 'Slug',
								'name' => 'rewrite[slug]',
								'attributes' => [
									'class' => 'regular-text',
									'placeholder' => 'Customize the pemastruct slug'
								]
							]
						),
						Node_Factory::make(
							Yes_No::class, [
								'label' => 'Prefix with "front"',
								'name' => 'rewrite[with_front]',
							]
						),
						Node_Factory::make(
							Yes_No::class, [
								'label' => 'Build feeds permastruct',
								'name' => 'rewrite[feeds]',
							]
						),
						Node_Factory::make(
							Yes_No::class, [
								'label' => 'Build pagination permastruct',
								'name' => 'rewrite[pages]',
							]
						),
					]
				]
			),
			Node_Factory::make(
				Yes_No::class, [
					'label' => 'Can export',
					'name' => 'can_export'
				]
			),
			Node_Factory::make(
				Yes_No::class, [
					'label' => 'Delete with user',
					'name' => 'delete_with_user'
				]
			),
			Node_Factory::make(
				Yes_No::class, [
					'label' => 'Show in REST API',
					'name' => 'show_in_rest'
				]
			),
			Node_Factory::make(
				Select::class, [
					'label'   => _x( 'Show in menu', 'cpt generator label', 'queulat' ),
					'name'    => 'show_in_menu',
					'options' => $this->get_parent_menus(),
				]
			),
		];
	}

	/**
	 * Build a list of top-level administration menus
	 *
	 * @return array Top-level menus indexed by hook
	 */
	private function get_parent_menus() {
		global $menu;
		$options = [
			'no_show'   => esc_html_x( 'Do not display in the admin menu', 'cpt option', 'queulat' ),
			'top_level' => esc_html_x( 'Display as a top level menu', 'cpt option', 'queulat' ),
		];
		foreach ( $menu as $item ) {
			if ( ! empty( $item[0] ) ) {
				$options[ $item[2] ] = $item[0];
			}
		}
		return $options;
	}

	public function sanitize_data( $input ) : array {
		$builder = new Custom_Post_Type();
		return $builder->sanitize_input( $input );
	}

	public function get_validation_rules( array $sanitized_data ) : array {
		$validations = [
			'slug' => [ new Is_Required(), new Max_Length( 20 ), new Value_Not_In( Custom_Post_Type::$reserved_keywords ) ],
		];
		if ( isset( $sanitized['show_in_menu'] ) && ! is_bool( $sanitized_data['show_in_menu'] ) ) {
			$validations['show_in_menu'] = [ new Value_In( array_keys( $this->get_parent_menus() ) ) ];
		}
		return $validations;
	}

	public function process_data( array $data ) : bool {
		$slug = $data['slug'];
		unset( $data['slug'] );

		// set the post capabilities
		$data['capability_type'] = [
			$slug,
			Strings::plural( $slug )
		];
		$data['map_meta_cap'] = true;

		$plugin = new Custom_Post_Type_Plugin( $slug, $data );
		$plugin->build();

		return true;
	}

	public function get_redirect_url() : string {
		return admin_url('plugins.php');
	}

	public function success_url_params() : array {
		return [
			'cpt-plugin-created' => 'ok'
		];
	}
}
