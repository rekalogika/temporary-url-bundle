<?php

/*
 * This file is part of rekalogika/temporary-url-bundle package.
 *
 * (c) Priyadi Iman Nurcahyo <https://rekalogika.dev>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Rekalogika\TemporaryUrl\Tests;

use Rekalogika\TemporaryUrl\Tests\Kernel;
use Rekalogika\TemporaryUrl\Twig\TemporaryUrlTwigExtension;
use Rekalogika\TemporaryUrl\Twig\TemporaryUrlTwigRuntime;
use Twig\RuntimeLoader\FactoryRuntimeLoader;
use Twig\Test\IntegrationTestCase;

class TwigTest extends IntegrationTestCase
{
    protected function getFixturesDir(): string
    {
        return __DIR__ . '/Twig';
    }

    protected function getExtensions(): iterable
    {
        return [
            new TemporaryUrlTwigExtension()
        ];
    }

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
                function () use ($temporaryUrlTwigRuntime): TemporaryUrlTwigRuntime {
                    return $temporaryUrlTwigRuntime;
                },
            ])
        ];
    }
}
