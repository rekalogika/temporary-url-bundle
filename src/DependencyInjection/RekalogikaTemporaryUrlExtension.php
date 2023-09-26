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

namespace Rekalogika\TemporaryUrl\DependencyInjection;

use Rekalogika\TemporaryUrl\Attribute\AsTemporaryUrlResourceTransformer;
use Rekalogika\TemporaryUrl\Attribute\AsTemporaryUrlServer;
use Rekalogika\TemporaryUrl\Tests\Kernel;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Extension\Extension;
use Symfony\Component\DependencyInjection\Loader\PhpFileLoader;
use Twig\Environment;

class RekalogikaTemporaryUrlExtension extends Extension
{
    public function load(array $configs, ContainerBuilder $container): void
    {
        $loader = new PhpFileLoader(
            $container,
            new FileLocator(__DIR__ . '/../../config')
        );

        $loader->load('services.php');

        if (class_exists(Environment::class)) {
            $loader->load('twig.php');
        }

        $env = $container->getParameter('kernel.environment');

        if ('test' === $env && class_exists(Kernel::class)) {
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

        $container->registerAttributeForAutoconfiguration(
            AsTemporaryUrlResourceTransformer::class,
            static function (
                ChildDefinition $definition,
                AsTemporaryUrlResourceTransformer $attribute,
                \Reflector $reflector
            ): void {
                if ($reflector instanceof \ReflectionMethod) {
                    $method = $reflector->name;
                } elseif ($reflector instanceof \ReflectionClass) {
                    $method = '__invoke';
                } else {
                    throw new \InvalidArgumentException(sprintf('Invalid attribute usage: "%s" can only be applied to methods or classes.', AsTemporaryUrlResourceTransformer::class));
                }

                $definition->addTag('rekalogika.temporary_url.resource_transformer', [
                    'method' => $method
                ]);
            }
        );
    }
}
