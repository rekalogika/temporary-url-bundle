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

use Psr\SimpleCache\CacheInterface;
use Symfony\Component\Cache\Adapter\ArrayAdapter;
use Symfony\Component\Cache\Psr16Cache;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class MockFactory
{
    public static function createCache(): CacheInterface
    {
        return new Psr16Cache(new ArrayAdapter());
    }

    public static function createRequestStack(): RequestStack
    {
        $requestStack = \Mockery::mock(RequestStack::class);

        $sessionId = \bin2hex(\random_bytes(16));

        $requestStack->shouldReceive('getSession')
            ->andReturn(self::createSession($sessionId));

        return $requestStack;
    }

    private static function createSession(string $sessionId): SessionInterface
    {
        $session = \Mockery::mock(SessionInterface::class);

        $session->shouldReceive('getId')
            ->andReturn($sessionId);

        return $session;
    }

    public static function createUrlGenerator(): UrlGeneratorInterface
    {
        return new UrlGenerator();
    }
}
