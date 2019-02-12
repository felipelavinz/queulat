<?php

namespace Queulat\Helpers;

class Term_Options extends \ArrayIterator {
	private $terms = [];
	public function __construct( array $args, array $flags = [] ) {
		$args        = wp_parse_args(
			$args,
			[
				'orderby' => 'name',
				'order'   => 'ASC',
			]
		);
		$this->terms = get_terms( $args );
		if ( isset( $flags['show_option_none'] ) && $flags['show_option_none'] ) {
			array_unshift(
				$this->terms,
				(object) [
					'name'    => _x( '(None)', 'null term option', 'queulat' ),
					'term_id' => 0,
				]
			);
		}
		parent::__construct( $this->terms );
	}
	public function current() {
		return parent::current()->name;
	}
	public function key() {
		$key = parent::key();
		return $this->terms[ $key ]->term_id;
	}
	public function getArrayCopy() {
		return wp_list_pluck( $this->terms, 'name', 'term_id' );
	}
}
