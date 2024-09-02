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

namespace Rekalogika\TemporaryUrl;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * Generates a temporary URL for the given resource.
 */
interface TemporaryUrlGeneratorInterface
{
    /**
     * Generates a temporary URL for the given resource.
     *
     * @param object $resource The resource
     * @param null|int|\DateInterval $ttl Time-to-live in seconds or a \DateInterval instance
     * @param boolean $pinSession Pin the URL to the current session, making it invalid in another session
     * @return string The resulting temporary URL
     */
    public function generateUrl(
        object $resource,
        null|int|\DateInterval $ttl = null,
        bool $pinSession = false,
        int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH,
    ): string;
}
