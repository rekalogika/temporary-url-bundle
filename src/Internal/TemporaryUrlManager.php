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

use Psr\SimpleCache\CacheInterface;
use Rekalogika\TemporaryUrl\Exception\ServerNotFoundException;
use Rekalogika\TemporaryUrl\Exception\TicketNotFoundException;
use Rekalogika\TemporaryUrl\Internal\TemporaryUrlParameters;

/**
 * Manages temporary URLS
 *
 * @internal
 */
final class TemporaryUrlManager
{
    /**
     * @var array<class-string,array{0:object,1:string}>
     */
    private readonly array $resourceToServerMap;

    /**
     * @param iterable<class-string,array{0:object,1:string}> $resourceToServerMap
     */
    public function __construct(
        private readonly CacheInterface $cache,
        private readonly TemporaryUrlResourceTransformer $resourceTransformer,
        iterable $resourceToServerMap,
        private readonly string $cachePrefix = 'temporary-url-',
        private readonly int $defaultTtl = 1800,
    ) {
        if ($resourceToServerMap instanceof \Traversable) {
            $resourceToServerMap = iterator_to_array($resourceToServerMap);
        }

        $this->resourceToServerMap = $resourceToServerMap;
    }

    public function createTicket(
        object $resource,
        null|int|\DateInterval $ttl = null,
        ?string $sessionId = null,
    ): TemporaryUrlResult {
        $resource = $this->resourceTransformer->transform($resource);

        if (!$this->isObjectValid($resource)) {
            throw new ServerNotFoundException($resource::class);
        }

        $ttl = $ttl instanceof \DateInterval
            ? (int) $ttl->format('%s')
            : ($ttl ?? $this->defaultTtl);

        $expiration = time() + $ttl - 10;

        $ticketid = bin2hex(random_bytes(16));
        $temporaryUrlData = new TemporaryUrlParameters($resource, $ttl, $sessionId);

        $this->cache->set(
            $this->cachePrefix . $ticketid,
            $temporaryUrlData,
            $ttl,
        );

        return new TemporaryUrlResult($ticketid, $expiration);
    }

    private function isObjectValid(object $resource): bool
    {
        foreach (array_keys($this->resourceToServerMap) as $class) {
            if ($resource instanceof $class) {
                return true;
            }
        }

        return false;
    }

    public function getTemporaryUrlDataFromTicketId(
        string $ticketid,
    ): TemporaryUrlParameters {
        $result = $this->cache->get($this->cachePrefix . $ticketid, null);

        if (null === $result) {
            throw new TicketNotFoundException($ticketid);
        }

        if (!$result instanceof TemporaryUrlParameters) {
            throw new \UnexpectedValueException(sprintf(
                'Unexpected temporary URL data: expected instance of "%s", got "%s"',
                TemporaryUrlParameters::class,
                get_debug_type($result),
            ));
        }

        return $result;
    }

    public function getCallableFromTemporaryUrlData(
        TemporaryUrlParameters $temporaryUrlData,
    ): callable {
        $resource = $temporaryUrlData->getResource();
        $callable = null;

        foreach ($this->resourceToServerMap as $class => $server) {
            if ($resource instanceof $class) {
                $callable = $server;
            }
        }

        if (!$callable) {
            throw new ServerNotFoundException($resource::class);
        }

        if (!\is_callable($callable)) {
            throw new \UnexpectedValueException();
        }

        return $callable;
    }
}
