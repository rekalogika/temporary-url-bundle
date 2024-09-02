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

use Rekalogika\TemporaryUrl\TemporaryUrlGeneratorInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Extension\RuntimeExtensionInterface;

/**
 * Twig runtime for temporary URL
 */
final class TemporaryUrlTwigRuntime implements RuntimeExtensionInterface
{
    public function __construct(
        private TemporaryUrlGeneratorInterface $temporaryUrlGenerator,
    ) {}

    public function generateTemporaryUrl(
        object $object,
        null|int|\DateInterval $ttl = null,
        bool $pinSession = false,
        int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH,
    ): string {
        return $this->temporaryUrlGenerator->generateUrl(
            $object,
            $ttl,
            $pinSession,
            $referenceType,
        );
    }
}
