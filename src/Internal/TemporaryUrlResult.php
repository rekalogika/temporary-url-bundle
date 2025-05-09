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
 * Represents the result of the request of a temporary URL
 *
 * @internal
 */
final readonly class TemporaryUrlResult
{
    public function __construct(
        private string $ticketId,
        private int $expiration,
    ) {}

    public function getTicketId(): string
    {
        return $this->ticketId;
    }

    public function getExpiration(): int
    {
        return $this->expiration;
    }
}
