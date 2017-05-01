<?php

/*
 * This file is part of the Language Selector bundle.
 *
 * (c) Beñat Espiña <benatespina@gmail.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

use PhpCsFixer\Config;
use PhpCsFixer\Finder;
use PhpCsFixer\Fixer\Comment\HeaderCommentFixer;

$header = <<<'EOF'
This file is part of the Language Selector Bundle.

(c) Beñat Espiña <benatespina@gmail.com>

For the full copyright and license information, please view the LICENSE
file that was distributed with this source code.
EOF;

return Config::create()
    ->setRiskyAllowed(true)
    ->setFinder(
        Finder::create()
            ->in([
                __DIR__ . '/src',
                __DIR__ . '/tests/Application/app',
                __DIR__ . '/tests/Application/bin',
                __DIR__ . '/tests/Application/web',
            ])
    )
    ->setRules([
        '@Symfony'                              => true,
        '@Symfony:risky'                        => true,
        'binary_operator_spaces'                => [
            'align_double_arrow' => true,
        ],
        'concat_space'                          => ['spacing' => 'one'],
        'header_comment'                        => [
            'header'      => $header,
            'commentType' => HeaderCommentFixer::HEADER_COMMENT,
        ],
        'no_unreachable_default_argument_value' => false,
        'ordered_imports'                       => true,
        'phpdoc_order'                          => true,
        'phpdoc_annotation_without_dot'         => false,
        'strict_param'                          => true,
    ]);
