<?php

namespace Queulat\Helpers;

class Entries_Options extends \ArrayIterator {
	private $query;
	private $current_post;
	public function __construct( array $params = [], array $flags = [] ) {
		$args = wp_parse_args( $params, [
			'post_status' => 'publish',
			'orderby' => 'title',
			'order' => 'ASC',
			'posts_per_page' => -1
		] );
		$flags = wp_parse_args( $flags, [
			'show_option_none' => false
		]);
		$this->query = new \WP_Query( $args );
		if ( isset( $flags['show_option_none'] ) && $flags['show_option_none'] ) {
			array_unshift( $this->query->posts, (object)[
				'ID' => '',
				'post_title' => is_string( $flags['show_option_none'] ) ? $flags['show_option_none'] : _x('(None)', 'null entry option', 'queulat')
			] );
		}
		parent::__construct( $this->query->posts );
	}
	public function current() {
		$this->current_post = parent::current();
		return $this->current_post->post_title;
	}
	public function key() {
		$key = parent::key();
		return $this->query->posts[ $key ]->ID;
	}
	public function getArrayCopy() {
		return wp_list_pluck( $this->query->posts, 'post_title', 'ID' );
	}
}