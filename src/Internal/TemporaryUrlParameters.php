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

namespace Rekalogika\TemporaryUrl\Internal;

/**
 * Represents the parameters for a temporary URL
 *
 * @internal
 */
final class TemporaryUrlParameters
{
    public function __construct(
        private readonly object $resource,
        private readonly int $ttl = 1800,
        private readonly ?string $sessionId = null,
    ) {}

    public function getResource(): object
    {
        return $this->resource;
    }

    public function getTtl(): int
    {
        return $this->ttl;
    }

    public function getSessionId(): ?string
    {
        return $this->sessionId;
    }
}
