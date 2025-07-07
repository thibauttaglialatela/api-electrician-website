<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src')   // Répertoire à analyser (par exemple le dossier src)
;

$config = new PhpCsFixer\Config();
return $config
    ->setRiskyAllowed(true) // Active certaines règles considérées comme risquées
    ->setRules([
        '@Symfony' => true,
        '@PSR12' => true,
        'strict_param' => true,
        'declare_strict_types' => true,
        'array_syntax' => ['syntax' => 'short'],
        'binary_operator_spaces' => ['default' => 'align_single_space_minimal'],
        'concat_space' => ['spacing' => 'one'],
        'no_unused_imports' => true,
        'single_line_throw' => true,
        'modernize_types_casting' => true,
        'native_function_invocation' => ['include' => ['@compiler_optimized']],
    ])
    ->setFinder($finder);
