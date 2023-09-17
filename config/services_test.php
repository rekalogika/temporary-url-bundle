<?php

/*
 * This file is part of rekalogika/temporary-url-bundle package.
 *
 * (c) Priyadi Iman Nurcahyo <https://rekalogika.dev>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

declare(strict_types=1);

use Psr\SimpleCache\CacheInterface;
use Rekalogika\TemporaryUrl\Internal\TemporaryUrlController;
use Rekalogika\TemporaryUrl\Internal\TemporaryUrlManager;
use Rekalogika\TemporaryUrl\TemporaryUrlGeneratorInterface;
use Rekalogika\TemporaryUrl\Tests\MockFactory;
use Rekalogika\TemporaryUrl\Tests\Model\DataServer;
use Rekalogika\TemporaryUrl\Twig\TemporaryUrlTwigExtension;
use Rekalogika\TemporaryUrl\Twig\TemporaryUrlTwigRuntime;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(CacheInterface::class)
        ->factory([MockFactory::class, 'createCache']);

    $services->set(RequestStack::class)
        ->factory([MockFactory::class, 'createRequestStack']);

    $services->set(UrlGeneratorInterface::class)
        ->factory([MockFactory::class, 'createUrlGenerator']);

    // test aliases

    $services->alias(
        'test.' . TemporaryUrlManager::class,
        TemporaryUrlManager::class
    )->public();

    $services->alias(
        'test.' . TemporaryUrlController::class,
        TemporaryUrlController::class
    )->public();

    $services->alias(
        'test.' . TemporaryUrlGeneratorInterface::class,
        TemporaryUrlGeneratorInterface::class
    )->public();

    $services->alias(
        'test.' . TemporaryUrlTwigRuntime::class,
        TemporaryUrlTwigRuntime::class
    )->public();

    $services->alias(
        'test.' . TemporaryUrlTwigExtension::class,
        TemporaryUrlTwigExtension::class
    )->public();

    // secondary request stack to simulate other user

    $services->set(
        RequestStack::class . '.secondary',
        RequestStack::class
    )
        ->factory([MockFactory::class, 'createRequestStack']);

    $services->set(
        TemporaryUrlController::class . '.secondary',
        TemporaryUrlController::class
    )
        ->args([
            '$temporaryUrlManager' => service(TemporaryUrlManager::class),
            '$requestStack' => service(RequestStack::class . '.secondary'),
        ])
        ->public();

    // data server for testing

    $services->set(DataServer::class)
        ->tag('rekalogika.temporary_url.resource_server', [
            'method' => 'serve'
        ])
        ->public();
};
