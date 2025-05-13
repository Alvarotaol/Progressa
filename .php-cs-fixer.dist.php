<?php


use PhpCsFixer\Config;
use PhpCsFixer\Finder;

$finder = Finder::create()
	->in(__DIR__ . '/app')
	->in(__DIR__ . '/routes')
	->in(__DIR__ . '/tests');

return (new Config())
	->setIndent("\t") // usar TAB
	->setLineEnding("\n")
	->setRules([
		'@PSR12' => true,
		'braces' => [
			'position_after_functions_and_oop_constructs' => 'same',
			'position_after_control_structures' => 'same',
			'position_after_anonymous_constructs' => 'same',
			'allow_single_line_anonymous_class_with_empty_body' => true,
			'allow_single_line_closure' => true,

		],
		'curly_braces_position' => [
			'functions_opening_brace' => 'same_line',
			'classes_opening_brace' => 'same_line',
		],
		'array_syntax' => ['syntax' => 'short'],
		'binary_operator_spaces' => ['default' => 'single_space'],
		'blank_line_after_namespace' => true,
		'no_extra_blank_lines' => ['tokens' => ['curly_brace_block']],
		'no_unused_imports' => true,
		'ordered_imports' => true,
		'single_blank_line_at_eof' => true,
		'single_trait_insert_per_statement' => false,
	])
	->setFinder($finder);
