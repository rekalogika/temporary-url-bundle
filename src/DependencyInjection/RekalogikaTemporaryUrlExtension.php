<?php

/*
 * This file is part of rekalogika/temporary-url-bundle package.
 *
 * (c) Priyadi Iman Nurcahyo <https://rekalogika.dev>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Rekalogika\TemporaryUrl\DependencyInjection;

use Rekalogika\TemporaryUrl\Attribute\AsTemporaryUrlServer;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;

class RekalogikaTemporaryUrlExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $env = $container->getParameter('kernel.environment');
        $loader = new PhpFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../../config')
        );
        $loader->load('services.php');

        if ('test' === $env) {
            $loader->load('services_test.php');
        }

        $container->registerAttributeForAutoconfiguration(
            AsTemporaryUrlServer::class,
            static function (
                ChildDefinition $definition,
                AsTemporaryUrlServer $attribute,
                \Reflector $reflector
            ): void {
                if ($reflector instanceof \ReflectionMethod) {
                    $method = $reflector->name;
                } elseif ($reflector instanceof \ReflectionClass) {
                    $method = '__invoke';
                } else {
                    throw new \InvalidArgumentException(sprintf('Invalid attribute usage: "%s" can only be applied to methods or classes.', AsTemporaryUrlServer::class));
                }

                $definition->addTag('rekalogika.temporary_url.resource_server', [
                    'method' => $method
                ]);
            }
        );
    }
}
