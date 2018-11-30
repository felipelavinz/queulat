<?php

namespace Queulat\Generator\CLI;

/**
 * Generate new Queulat plugins for custom post types, custom taxonomies, etc.
 */
class Generate_Command {

	/**
	 * Generates a new custom post type plugin.
	 *
	 * ## OPTIONS
	 *
	 * <post_type>
	 * : The "post_type" identifier. Use a singular, lowercase noun, with dashes instead of spaces
	 *
	 * [--description=<description>]
	 * : A short descriptive summary of what the post type is
	 *
	 * [--singular-label=<singular_label>]
	 * : The human-readable name of the post type, in singular
	 *
	 * [--plural-label=<plural_label>]
	 * : The human-readable name of the post type, in plural
	 *
	 * [--post-type-plural=<post_type_plural>]
	 * : Lowercase noun, in plural, with dashes instead of spaces (used for creating capabilities)
	 *
	 * [--rewrite-slug=<rewrite_slug>]
	 * : Permalink structure slug
	 *
	 * [--supports=<supports>]
	 * : Features supported by the post_type. One or more (comma-separated) of: title, editor, author, thumbnail, excerpt, trackbacks, custom-fields, comments, revisions, page-attributes, post-formats
	 *
	 * [--public]
	 * : Controls how the type is visible to authors and readers
	 * ---
	 * default: 0
	 * ---
	 *
	 * [--map-meta-cap]
	 * : Whether to use the internal default meta capability handling
	 * ---
	 * default: 1
	 * ---
	 *
	 * [--hierarchical]
	 * : Whether the post type is hierarchical (allows parents) or flat
	 * ---
	 * default: 0
	 * ---
	 *
	 * [--has-archive]
	 * : Enables post archives
	 * ---
	 * default: 0
	 * ---
	 *
	 * [--can-export]
	 * : Can this post_type be exported
	 * ---
	 * default: 1
	 * ---
	 *
	 * [--delete-with-user]
	 * : Whether to delete posts of this type when deleting a user.
	 * ---
	 * default: null
	 * ---
	 *
	 * [--show-in-rest]
	 * : Whether to expose this post_type in the REST API
	 * ---
	 * default: 0
	 * ---
	 *
	 * @subcommand post-type
	 * @param array $args
	 * @param array $assoc_args
	 */
	function post_type( array $args, array $assoc_args ) {
		print_r( func_get_args() );
	}
}