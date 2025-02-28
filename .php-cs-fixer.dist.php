<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->notPath([
        'vendor',
        '#^library#',
        '#^application/library/harmoni/Primitives/Collections-Text/SafeHTML/classes/HTMLSax3#',
        '#^var#',
    ])
;

return (new PhpCsFixer\Config())
    ->setRules([
        '@PHP71Migration' => true,
        '@PHPUnit75Migration:risky' => true,
        '@Symfony' => true,
        // '@Symfony:risky' => true,
        'protected_to_private' => false,
        'trailing_comma_in_multiline' => ['elements' => ['arrays', 'match', 'parameters']],
    ])
    ->setRiskyAllowed(true)
    ->setFinder($finder)
;
