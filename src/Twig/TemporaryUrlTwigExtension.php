<?php

declare(strict_types=1);

/*
 * This file is part of rekalogika/temporary-url-bundle package.
 *
 * (c) Priyadi Iman Nurcahyo <https://rekalogika.dev>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Rekalogika\TemporaryUrl\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Twig\TwigFunction;

/**
 * Twig extension for temporary URL
 */
class TemporaryUrlTwigExtension extends AbstractExtension
{
    #[\Override]
    public function getFilters(): array
    {
        return [
            new TwigFilter(
                'temporary_url',
                [TemporaryUrlTwigRuntime::class, 'generateTemporaryUrl'],
            ),
        ];
    }

    #[\Override]
    public function getFunctions(): array
    {
        return [
            new TwigFunction(
                'temporary_url_autoexpire',
                $this->autoExpire(...),
                ['is_safe' => ['html']],
            ),
        ];
    }

    public function autoExpire(): string
    {
        return 'data-controller="rekalogika--temporary-url-bundle--autoexpire"';
    }
}
