<?php

namespace Queulat\Helpers;

use Queulat\Forms\Node_Factory;
use Queulat\Forms\Element\Input_Radio;
use Queulat\Forms\Element\Select;

class Taxonomy_Single_Term_View {
	public function __invoke( $post, $metabox_args ) {
		$terms    = get_terms(
			[
				'taxonomy'   => $metabox_args['args']['taxonomy'],
				'hide_empty' => false,
			]
		);
		$selected = get_the_terms( $post, $metabox_args['args']['taxonomy'] );
		if ( is_wp_error( $terms ) || empty( $terms ) ) {
			echo '<div class="notice notice-warning inline"><p>';
				printf( esc_html_x( 'There are no %1$s registered yet', 'single term tax metabox view', 'queulat' ), $metabox_args['title'] );
			echo '</p></div>';
		}
		$value             = $selected[0]->term_id ?? null;
		$max_radio_options = apply_filters( 'queulat_taxonomy_single_term_view_max_radio', 5, $terms, $post, $metabox_args );
		$node              = count( $terms ) <= $max_radio_options ? Input_Radio::class : Select::class;
		$radio             = Node_Factory::make(
			$node,
			[
				'name'       => "tax_input[{$metabox_args['args']['taxonomy']}]",
				'label'      => $metabox_args['title'],
				'options'    => wp_list_pluck( $terms, 'name', 'term_id' ),
				'value'      => $value,
				'attributes' => [
					'id' => "tax-input__{$metabox_args['args']['taxonomy']}",
				],
			]
		);
		echo $radio;
	}
}
