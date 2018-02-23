<?php

return [
	'directory_list' => [
		'../../../wp',
		'vendor',
		'src',
	],
	'exclude_analysis_directory_list' => [
		'../../../wp',
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