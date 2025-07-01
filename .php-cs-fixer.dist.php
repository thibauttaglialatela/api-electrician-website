<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__.'/src')   // Répertoire à analyser (par exemple le dossier src)
;

$config = new PhpCsFixer\Config();
return $config
    ->setRiskyAllowed(true) // Active certaines règles considérées comme risquées
    ->setRules([
        '@PSR12' => true,
        'strict_param' => true,
        '@Symfony' => true, // Utilisation des règles de codage Symfony
        'array_syntax' => ['syntax' => 'short'], // Utilisation des arrays courts []
        'binary_operator_spaces' => ['default' => 'align_single_space_minimal'], // Aligne les opérateurs binaires
        'concat_space' => ['spacing' => 'one'], // Un espace autour des concaténations de chaînes
        'declare_strict_types' => true, // Ajoute `declare(strict_types=1)` dans les fichiers PHP
        'no_unused_imports' => true, // Supprime les imports non utilisés
        'phpdoc_no_empty_return' => false, // Autorise `@return void` dans PHPDoc
    ])
    ->setFinder($finder);
