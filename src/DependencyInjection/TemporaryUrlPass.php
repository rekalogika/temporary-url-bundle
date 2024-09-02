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

use Rekalogika\TemporaryUrl\Internal\TemporaryUrlManager;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\HttpFoundation\Response;

class TemporaryUrlPass implements CompilerPassInterface
{
    #[\Override]
    public function process(ContainerBuilder $container): void
    {
        /**
         * @var array<class-string,array{0:Definition,1:string}>
         */
        $urlDataClassToUrlServerMap = [];

        $servers = $container
            ->findTaggedServiceIds('rekalogika.temporary_url.resource_server', true);

        foreach ($servers as $serviceId => $tags) {
            $r = $container->getReflectionClass($serviceId);

            if (null === $r) {
                throw new \RuntimeException(sprintf('Invalid service: class "%s" does not exist.', $serviceId));
            }

            $service = $container->getDefinition($serviceId);

            foreach ($tags as $tag) {
                $method = $tag['method'] ?? null;

                if (!is_string($method)) {
                    throw new \RuntimeException('Invalid definition: tag "method" is not a string.');
                }

                // method checking

                $reflectionMethod = $r->getMethod($method);

                if (!$reflectionMethod->isPublic()) {
                    throw new \RuntimeException(sprintf('Invalid definition: method "%s" in service ID "%s" is not public.', $method, $serviceId));
                }

                // return type checking

                $returnType = $reflectionMethod->getReturnType();

                if (!$returnType instanceof \ReflectionNamedType) {
                    throw new \RuntimeException(sprintf(
                        'Invalid server service "%s": only named type in the return type of method "%s()" is supported',
                        $serviceId,
                        $method,
                    ));
                }

                if ($returnType->getName() !== Response::class) {
                    throw new \RuntimeException(sprintf(
                        'Invalid server service "%s": method "%s()" must return a "%s" instance',
                        $serviceId,
                        $method,
                        Response::class,
                    ));
                }

                // parameter checking

                $parameters = $reflectionMethod->getParameters();

                if (count($parameters) != 1) {
                    throw new \RuntimeException(sprintf('Invalid server service "%s": method "%s()" must only have one argument.', $serviceId, $method));
                }

                $firstParameter = $parameters[0] ?? null;

                if (!$firstParameter) {
                    throw new \RuntimeException(sprintf('Invalid server service "%s": method "%s()" must have one argument.', $serviceId, $method));
                }

                $type = $firstParameter->getType();

                if (!$type) {
                    throw new \RuntimeException(sprintf(
                        'Invalid server service "%s": argument "$%s" of method "%s()" must have a type-hint corresponding to the resource class it serves.',
                        $serviceId,
                        $firstParameter->getName(),
                        $method,
                    ));
                }

                if ($type instanceof \ReflectionNamedType) {
                    $urlDataClassToUrlServerMap[$type->getName()] = [$service, $method];
                    continue;
                } elseif ($type instanceof \ReflectionUnionType) {
                    foreach ($type->getTypes() as $type) {
                        if (!$type instanceof \ReflectionNamedType) {
                            throw new \RuntimeException(sprintf(
                                'Invalid server service "%s": intersection type in argument "$%s" of method "%s()" is unsupported.',
                                $serviceId,
                                $firstParameter->getName(),
                                $method,
                            ));
                        }

                        $urlDataClassToUrlServerMap[$type->getName()] = [$service, $method];
                    }

                    continue;
                }

                throw new \RuntimeException(sprintf(
                    'Invalid server service "%s": only named or union type in the argument "$%s" of method "%s()" is supported',
                    $serviceId,
                    $firstParameter->getName(),
                    $method,
                ));
            }
        }

        $temporaryUrlManager = $container
            ->getDefinition(TemporaryUrlManager::class);

        $temporaryUrlManager->setArgument('$resourceToServerMap', $urlDataClassToUrlServerMap);
    }
}
