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

use Rekalogika\TemporaryUrl\TemporaryUrlGeneratorInterface;
use Rekalogika\TemporaryUrl\Twig\TemporaryUrlTwigExtension;
use Rekalogika\TemporaryUrl\Twig\TemporaryUrlTwigRuntime;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use function Symfony\Component\DependencyInjection\Loader\Configurator\service;

return static function (ContainerConfigurator $containerConfigurator): void {
    $services = $containerConfigurator->services();

    $services->set(TemporaryUrlTwigRuntime::class)
        ->tag('twig.runtime')
        ->args([
            service(TemporaryUrlGeneratorInterface::class),
        ]);

    $services->set(TemporaryUrlTwigExtension::class)
        ->tag('twig.extension');
};
