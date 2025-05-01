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

use Psr\Cache\CacheItemPoolInterface;
use Rekalogika\TemporaryUrl\DataServer;
use Rekalogika\TemporaryUrl\Internal\TemporaryUrlController;
use Rekalogika\TemporaryUrl\TemporaryUrlGenerator;
use Rekalogika\TemporaryUrl\TemporaryUrlGeneratorInterface;
use Rekalogika\TemporaryUrl\Internal\TemporaryUrlManager;
use Rekalogika\TemporaryUrl\Internal\TemporaryUrlResourceTransformer;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(TemporaryUrlManager::class)
        ->args([
            service(CacheItemPoolInterface::class),
            service(TemporaryUrlResourceTransformer::class),
        ]);

    $services->set(TemporaryUrlController::class)
        ->tag('controller.service_arguments')
        ->args([
            service(TemporaryUrlManager::class),
            service(RequestStack::class),
        ]);

    $services->set(DataServer::class)
        ->tag('rekalogika.temporary_url.resource_server', [
            'method' => 'respond',
        ]);

    $services->set(TemporaryUrlGeneratorInterface::class, TemporaryUrlGenerator::class)
        ->args([
            service(TemporaryUrlManager::class),
            service(UrlGeneratorInterface::class),
            service(RequestStack::class),
        ]);

    $services->set(TemporaryUrlResourceTransformer::class);
};
