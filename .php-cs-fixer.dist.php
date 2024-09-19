<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->notPath([
        'vendor',
        '#^library#',
    ])
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        // '@Symfony:risky' => true,
    ])
    ->setFinder($finder)
;
