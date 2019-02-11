<?php

$config = [
	'target_php_version' => '7.0',
	'directory_list' => [
		'src',
		'vendor'
	],
	'exclude_analysis_directory_list' => [
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

// is it a bedrock install?
$bedrock_path = '../../../wp';
if ( is_readable( $bedrock_path ) ) {
	$config['directory_list'][] = $bedrock_path;
	$config['exclude_analysis_directory_list'][] = $bedrock_path;
} else {
	$config['directory_list'][] = '../../..';
	$config['exclude_analysis_directory_list'][] = '../../../wp-admin';
	$config['exclude_analysis_directory_list'][] = '../../../wp-includes';
}

return $config;