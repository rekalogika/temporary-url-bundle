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

namespace Rekalogika\TemporaryUrl\Tests;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;

/**
 * Mock URL generator
 */
final class UrlGenerator implements UrlGeneratorInterface
{
    /**
     * @param array<array-key,mixed> $parameters
     */
    #[\Override]
    public function generate(
        string $name,
        array $parameters = [],
        int $referenceType = self::ABSOLUTE_PATH,
    ): string {
        if ($name !== 'rekalogika_temporary_url') {
            throw new \InvalidArgumentException('Unsupported route name');
        }

        $ticketId = $parameters['ticketid'] ?? null;
        \assert(\is_string($ticketId));

        if ($ticketId === '' || $ticketId === '0') {
            throw new \InvalidArgumentException('Missing ticketid parameter');
        }

        return match ($referenceType) {
            self::ABSOLUTE_PATH => '/__temporary-url__/' . $ticketId,
            self::ABSOLUTE_URL => 'https://example.com/__temporary-url__/' . $ticketId,
            self::NETWORK_PATH => '//example.com/__temporary-url__/' . $ticketId,
            self::RELATIVE_PATH => '__temporary-url__/' . $ticketId,
            default => throw new \InvalidArgumentException('Unsupported reference type'),
        };
    }

    #[\Override]
    public function setContext(RequestContext $context): void {}

    #[\Override]
    public function getContext(): RequestContext
    {
        return \Mockery::mock(RequestContext::class);
    }
}
