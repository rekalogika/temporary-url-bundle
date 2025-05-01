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

use Rekalogika\TemporaryUrl\Twig\TemporaryUrlTwigExtension;
use Rekalogika\TemporaryUrl\Twig\TemporaryUrlTwigRuntime;
use Twig\RuntimeLoader\FactoryRuntimeLoader;
use Twig\Test\IntegrationTestCase;

class TwigTest extends IntegrationTestCase
{
    #[\Override]
    protected function getFixturesDir(): string
    {
        return __DIR__ . '/Twig';
    }

    #[\Override]
    protected function getExtensions(): iterable
    {
        return [
            new TemporaryUrlTwigExtension(),
        ];
    }

    #[\Override]
    protected function getRuntimeLoaders(): iterable
    {
        $kernel = new Kernel();
        $kernel->boot();

        $container = $kernel->getContainer();
        $temporaryUrlTwigRuntime = $container->get('test.' . TemporaryUrlTwigRuntime::class);
        $this->assertInstanceOf(TemporaryUrlTwigRuntime::class, $temporaryUrlTwigRuntime);

        return [
            new FactoryRuntimeLoader([
                TemporaryUrlTwigRuntime::class =>
                fn(): TemporaryUrlTwigRuntime => $temporaryUrlTwigRuntime,
            ]),
        ];
    }

    protected static function getFixturesDirectory(): string
    {
        return __DIR__ . '/Twig';
    }
}
