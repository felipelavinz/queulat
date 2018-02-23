<?php
/**
 * PostObject
 *
 * This basic model can be extended by each of the custom types
 * as a "Decorator" for WP_Post objects.
 * Since the WP_Post class it's declared as final, we can't
 * extend it with new methods directly, so we'll use this class
 * as an intermediary for it.
 * Custom post types plugins generated with Queulat create
 * a new class for its type, where new methods can be added
 * as necessary
 *
 * @package Queulat
 */
namespace Queulat;

abstract class Post_Object {
	/**
	 * Hold a copy of the original WP_Post object
	 *
	 * @var \WP_Post
	 */
	protected $post;

	/**
	 * Hold a copy of the object properties accessed with the magic getter
	 *
	 * @var array
	 */
	private $known_properties = [];

	public function __construct( $post = null ) {
		$this->post = get_post( $post );
	}

	/**
	 * Build a Post_Object instance from a WP_Post object, an ID or from the currently looped post
	 *
	 * @param  int|\WP_Post|null $post A post or ID to build a new Post_Object
	 * @return Post_Object                  Instantiated Post_Object
	 */
	public static function get_instance( $post = null ) : Post_Object {
		$post = get_post( $post );
		return new static( $post );
	}

	/**
	 * Magically get a property from the post object
	 *
	 * When a property it's accessed through the magic getter, we'll
	 * try to get it using get_post_meta() but on that point we don't
	 * know if it's a single or multiple property.
	 * This method will get the data and return as a string if it has
	 * a single value or as an array if it has more than one value
	 *
	 * @param  string $key Name of the property, ie a "meta_key"
	 * @return string|array Value for the meta property
	 */
	public function __get( string $key ) {
		// if we already got that property, inmediately return it
		if ( isset( $this->known_properties[ $key ] ) ) {
			return $this->known_properties[ $key ];
		}
		// if it's a post field, return that
		if ( isset( $this->post->{$key} ) ) {
			return $this->post->{$key};
		}
		if ( 'thumbnail' === $key ) {
			$this->known_properties['thumbnail'] = $this->get_thumbnail();
			return $this->known_properties['thumbnail'];
		}
		if ( 'permalink' === $key ) {
			$this->known_properties['permalink'] = get_permalink( $this->post->ID );
			return $this->known_properties['permalink'];
		}
		// we don't know which values could have multiple values
		// so... let's check that
		$value = get_post_meta( $this->post->ID, $key, false );
		if ( empty( $value ) ) {
			// probably not a postmeta, let the magic in WP_Post handle it
			return $this->post->{$key};
		} elseif ( count( $value ) === 1 ) {
			// return as a single field
			// (could be an array itself if data is serialized)
			$this->known_properties[ $key ] = $value[0];
		} else {
			$this->known_properties[ $key ] = $value;
		}
		return $this->known_properties[ $key ];
	}

	/**
	 * Check if some property exists
	 *
	 * @param string $key The name of the checked property
	 * @return bool
	 */
	public function __isset( string $key ) : bool {
		return metadata_exists( 'post', $this->post->ID, $key );
	}

	/**
	 * Build debug info when var_dump'ing the object
	 *
	 * @return array PostObject debug info
	 */
	public function __debugInfo() : array {
		$debug_object = array();
		foreach ( get_object_vars( $this ) as $key => $val ) {
			$debug_object[ $key ] = $val;
		}
		foreach ( get_post_custom( $this->post->ID ) as $key => $val ) {
			$debug_object[ $key ] = $val;
		}
		return $debug_object;
	}

	/**
	 * Return the instance of the WP_Post
	 *
	 * @return \WP_Post The post object that we're working with
	 */
	public function get_post() : \WP_Post {
		return $this->post;
	}

	/**
	 * Get a given property as an array
	 *
	 * @param string $key The meta_key to get
	 * @return array The meta value
	 */
	public function get_multiple( string $key ) : array {
		return get_post_meta( $this->post->ID, $key, false );
	}

	/**
	 * Get a given property as a string
	 *
	 * @param  string $key The meta_key value we're trying to get
	 * @return string The meta value
	 */
	public function get_single( string $key ) : string {
		return get_post_meta( $this->post->ID, $key, true );
	}

	/**
	 * Get the post thumbnail for the current entry
	 *
	 * @param  string $size The image size name
	 * @param  array  $attr Attributes used on the HTML image element
	 * @return string Post thumbnail HTML
	 */
	public function get_thumbnail( string $size = 'post-thumbnail', array $attr = array() ) : string {
		return get_the_post_thumbnail( $this->post->ID, $size, $attr );
	}
}
