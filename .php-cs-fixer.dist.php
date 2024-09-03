<?php

$finder = PhpCsFixer\Finder::create()
    ->in(__DIR__ . '/src')
    ->in(__DIR__ . '/tests');

$config = new PhpCsFixer\Config();

return $config->setRules([
    '@PhpCsFixer:risky' => true,
    'declare_strict_types' => true,
    'php_unit_strict' => false,
    'header_comment' => [
        'header' => <<<EOF
This file is part of rekalogika/temporary-url-bundle package.

(c) Priyadi Iman Nurcahyo <https://rekalogika.dev>

For the full copyright and license information, please view the LICENSE file
that was distributed with this source code.
EOF,
    ]
])
    ->setFinder($finder)
;
