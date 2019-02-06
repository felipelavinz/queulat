<?php

namespace Queulat\Generator\Builder;

use Underscore\Types\Arrays;

class Custom_Post_Type {
	private $params = [];

	/**
	 * List of reserved keywords that can't be used as CPT slug
	 *
	 * @var array
	 */
	public static $reserved_keywords = [
		'action',
		'attachment',
		'author',
		'custom_css',
		'customize_changeset',
		'nav_menu_item',
		'order',
		'page',
		'post',
		'revision',
		'theme',
	];

	public function __construct( array $params = [] ) {
		if ( $params ) {
			$this->params = $this->sanitize_input( $params );
		}
	}
	public static function get_supports() : array {
		return [
			'title'           => __( 'Title', 'queulat' ),
			'editor'          => __( 'Editor (content)', 'queulat' ),
			'author'          => __( 'Author', 'queulat' ),
			'thumbnail'       => __( 'Featured image', 'queulat' ),
			'excerpt'         => __( 'Excerpt', 'queulat' ),
			'trackbacks'      => __( 'Trackbacks', 'queulat' ),
			'custom-fields'   => __( 'Custom fields', 'queulat' ),
			'comments'        => __( 'Comments', 'queulat' ),
			'revisions'       => __( 'Revisions', 'queulat' ),
			'page-attributes' => __( 'Page attributes: menu order, parent (if hierarchical is true)', 'queulat' ),
			'post-formats'    => __( 'Post formats', 'queulat' ),
		];
	}
	public function sanitize_input( array $input ) : array {
		$flat      = Arrays::flatten( $input );
		$sanitized = [];
		foreach ( $flat as $key => $val ) {
			switch ( $key ) {
				case 'slug':
					$sanitized[ $key ] = $val;
					break;
				case 'description':
					$sanitized[ $key ] = sanitize_textarea_field( $val );
					break;
				case 'label':
					$sanitized[ $key ] = sanitize_text_field( $val );
					break;
				case 'show_in_menu':
					switch ( $val ) {
						case 'no_show':
							$sanitized[ $key ] = false;
							break;
						case 'top_level':
							$sanitized[ $key ] = true;
							break;
						default:
							$sanitized[ $key ] = sanitize_text_field( $val );
							break;
					}
					break;
				case 'public':
				case 'hierarchical':
				case 'has_archive':
				case 'rewrite.with_front':
				case 'rewrite.feeds':
				case 'rewrite.pages':
				case 'can_export':
				case 'delete_with_user':
				case 'show_in_rest':
				case 'rewrite_enable':
					$sanitized[ $key ] = (bool) $val;
				default:
					if ( stripos( $key, 'supports.' ) !== false ) {
						if ( array_key_exists( $val, static::get_supports() ) ) {
							$sanitized[ $key ] = $val;
						}
					}
					break;
			}
		}
		if ( ! isset( $sanitized['rewrite_enable'] ) || ! $sanitized['rewrite_enable'] ) {
			unset( $sanitized['rewrite'], $sanitized['rewrite_enable'] );
			$sanitized['rewrite'] = false;
		} else {
			unset( $sanitized['rewrite_enable'] );
		}
		return Arrays::reverseFlatten( $sanitized );
	}
}
