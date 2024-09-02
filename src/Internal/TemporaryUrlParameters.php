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
class TemporaryUrlParameters
{
    public function __construct(
        private object $resource,
        private int $ttl = 1800,
        private ?string $sessionId = null,
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
