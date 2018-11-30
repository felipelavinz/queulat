<?php

return [
	'directory_list' => [
		'src',
		'vendor',
		// if this installation it's based on bedrock.
		'../../../wp',
		// ... otherwhise, a normal WP install
		'../../..',
	],
	'exclude_analysis_directory_list' => [
		'../../../wp',
		'../../../wp-admin',
		'../../../wp-includes',
		'vendor',
	],
	'analyze_signature_compatibility'             => true,
	'allow_missing_properties'                    => false,
	'quick_mode'                                  => true,
	'null_casts_as_any_type'                      => true,
	'scalar_implicit_cast'                        => false,
	'ignore_undeclared_variables_in_global_scope' => false,
	'should_visit_all_nodes'                      => true,
];