<?php

$header = <<<'HEADER'
This file is part of the enhavo package.

(c) WE ARE INDEED GmbH

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
HEADER;

$finder = PhpCsFixer\Finder::create()->in(__DIR__.'/src');

return (new PhpCsFixer\Config())
    ->setRiskyAllowed(true)
    ->setRules([
        '@Symfony' => true,
        'array_syntax' => [
            'syntax' => 'short',
        ],
        'braces' => [
            'allow_single_line_closure' => true,
        ],
//        'header_comment' => [
//            'header' => $header,
//            'location' => 'after_open',
//        ],
        'no_useless_else' => true,
        'no_useless_return' => true,
        'ordered_imports' => true,
        'phpdoc_add_missing_param_annotation' => [
            'only_untyped' => true,
        ],
        'phpdoc_summary' => false,
        'phpdoc_order' => true,
        'semicolon_after_instruction' => true,
        'ternary_to_null_coalescing' => true,
    ])
    ->setFinder($finder)
;
