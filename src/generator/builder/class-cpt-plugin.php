<?php

namespace Queulat\Generator\Builder;

use WP_Post_Type;
use Twig\Environment;
use Queulat\Helpers\Strings;
use Queulat\Generator\Renderer;
use Twig\Loader\FilesystemLoader;

class Custom_Post_Type_Plugin {

	/**
	 * Hold the temporary instance of the WordPress Post Type
	 *
	 * @var \WP_Post_Type
	 */
	private $wp_post_type;

	/**
	 * Hold the raw slug used as post type slug
	 *
	 * @var string
	 */
	private $raw_slug = '';

	/**
	 * Build a new Custom Post Type plugin builder
	 *
	 * @param string $slug The post type slug
	 * @param array  $args  Post type arguments
	 * @see https://codex.wordpress.org/register_post_type#Arguments
	 */
	public function __construct( string $slug, array $args ) {
		$this->raw_slug     = $slug;
		$sanitized_slug     = sanitize_key( $slug );
		$this->wp_post_type = new WP_Post_Type( $sanitized_slug, $args );
	}

	/**
	 * Render the post type arguments as string
	 *
	 * @return string
	 */
	private function render_post_type_arguments() : string {
		$object_vars                    = get_object_vars( $this->wp_post_type );
		$longest_key                    = Renderer::get_longest_key_length( array_keys( $object_vars ) );
		$object_vars['capability_type'] = [
			$this->wp_post_type->name,
			Strings::plural( $this->wp_post_type->name ),
		];
		$properties                     = '';
		$localize                       = [
			'label',
			'labels',
			'description',
		];
		foreach ( $object_vars as $key => $val ) {
			// internal properties
			if ( strpos( $key, '_' ) === 0 || $key == 'name' || $key == 'cap' ) {
				continue;
			}
			$properties .= Renderer::render_array_member( $key, $val, $longest_key, in_array( $key, $localize ), "cpt_{$this->wp_post_type->name}" );
		}
		return rtrim( $properties, "\n," );
	}

	/**
	 * Build all template variables used on the files to be generated
	 *
	 * @return array Associative array with var name as keys
	 */
	public function get_template_vars() : array {
		$label               = $this->wp_post_type->label;
		$file_name           = strtolower( Strings::to_kebab_case( $this->wp_post_type->name ) );
		$class_name          = Strings::to_capitalized_snake_case( $this->raw_slug );
		$description         = $this->wp_post_type->description;
		$post_type           = $this->wp_post_type->name;
		$post_type_arguments = Renderer::ident( $this->render_post_type_arguments(), 3 );
		return compact( 'label', 'file_name', 'class_name', 'description', 'post_type', 'post_type_arguments' );
	}

	/**
	 * Get the name of the templates that will be used
	 *
	 * @return array
	 */
	public function get_templates() : array {
		return [
			'stub-cpt-plugin.twig',
			'class-stub-post-type.twig',
			'class-stub-post-query.twig',
			'class-stub-post-object.twig',
		];
	}

	public function build() {
		$template_vars = $this->get_template_vars();

		// replace "stub" in stub file names with the file name
		$stub   = $template_vars['file_name'];
		$prefix = apply_filters( 'quelat_generate_builder_ctp_plugin', 'queulat-' );

		$loader       = new FilesystemLoader( __DIR__ . '/../stubs' );
		$twig         = new Environment( $loader, [] );
		$templates    = $this->get_templates();
		$output_files = [];
		foreach ( $templates as $template ) {
			$output_file_name                  = str_ireplace(
				[ 'stub', 'twig' ],
				[ $stub, 'php' ],
				$template
			);
			$output_files[ $output_file_name ] = $twig->render( $template, $template_vars );
		}

		$url   = wp_nonce_url( 'tools.php?page=queulat-cpt-plugin-generator', 'queulat-cpt-plgin-generator' );
		$creds = request_filesystem_credentials( $url, '', false, WP_PLUGIN_DIR, [] );

		if ( ! $creds ) {
			// @todo si no tengo credenciales... generar un zip?
			wp_die( 'No tienes permiso para escribir archivos en tu instalación de WordPress' );
		}

		WP_Filesystem( $creds );

		global $wp_filesystem;

		$plugin_dir = "{$prefix}{$template_vars['file_name']}-cpt-plugin";

		$wp_filesystem->mkdir( WP_PLUGIN_DIR . "/{$plugin_dir}" );
		foreach ( $output_files as $filename => $contents ) {
			$wp_filesystem->put_contents( WP_PLUGIN_DIR . "/{$plugin_dir}/{$filename}", $contents );
		}

		return true;
	}
}
