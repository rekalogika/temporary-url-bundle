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

use Rekalogika\TemporaryUrl\Internal\TemporaryUrlResourceTransformer;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;

final class TemporaryUrlResourceTransformerPass implements CompilerPassInterface
{
    #[\Override]
    public function process(ContainerBuilder $container): void
    {
        /**
         * @var array<class-string,array{0:Definition,1:string}>
         */
        $transformerMap = [];

        $transformers = $container
            ->findTaggedServiceIds('rekalogika.temporary_url.resource_transformer', true);

        foreach ($transformers as $serviceId => $tags) {
            $service = $container->getDefinition($serviceId);
            $class = $service->getClass();
            $reflectionClass = $container->getReflectionClass($class);

            if (null === $reflectionClass) {
                throw new \RuntimeException(\sprintf('Invalid service: class "%s" does not exist.', $serviceId));
            }

            /** @var array<string,mixed> $tag */
            foreach ($tags as $tag) {
                $method = $tag['method'] ?? null;

                if (!\is_string($method)) {
                    throw new \RuntimeException('Invalid definition: tag "method" is not a string.');
                }

                // method checking

                $reflectionMethod = $reflectionClass->getMethod($method);

                if (!$reflectionMethod->isPublic()) {
                    throw new \RuntimeException(\sprintf('Invalid definition: method "%s" in service ID "%s" is not public.', $method, $serviceId));
                }

                // return type checking

                $returnType = $reflectionMethod->getReturnType();

                if (!$returnType instanceof \ReflectionNamedType) {
                    throw new \RuntimeException(\sprintf(
                        'Invalid server service "%s": only named type in the return type of method "%s()" is supported',
                        $serviceId,
                        $method,
                    ));
                }

                // parameter checking

                $parameters = $reflectionMethod->getParameters();

                if (\count($parameters) != 1) {
                    throw new \RuntimeException(\sprintf('Invalid transformer service "%s": method "%s()" must only have one argument.', $serviceId, $method));
                }

                // @phpstan-ignore nullCoalesce.offset
                $firstParameter = $parameters[0] ?? null;

                // @phpstan-ignore identical.alwaysFalse
                if ($firstParameter === null) {
                    throw new \RuntimeException(\sprintf('Invalid transformer service "%s": method "%s()" must have one argument.', $serviceId, $method));
                }

                $type = $firstParameter->getType();

                if (!$type) {
                    throw new \RuntimeException(\sprintf(
                        'Invalid transformer service "%s": argument "$%s" of method "%s()" must have a type-hint corresponding to the resource class it serves.',
                        $serviceId,
                        $firstParameter->getName(),
                        $method,
                    ));
                }

                if ($type instanceof \ReflectionNamedType) {
                    $transformerMap[$type->getName()] = [$service, $method];
                    continue;
                } elseif ($type instanceof \ReflectionUnionType) {
                    foreach ($type->getTypes() as $type) {
                        if (!$type instanceof \ReflectionNamedType) {
                            throw new \RuntimeException(\sprintf(
                                'Invalid transformer service "%s": intersection type in argument "$%s" of method "%s()" is unsupported.',
                                $serviceId,
                                $firstParameter->getName(),
                                $method,
                            ));
                        }

                        $transformerMap[$type->getName()] = [$service, $method];
                    }

                    continue;
                }

                throw new \RuntimeException(\sprintf(
                    'Invalid transformer service "%s": only named or union type in the argument "$%s" of method "%s()" is supported',
                    $serviceId,
                    $firstParameter->getName(),
                    $method,
                ));
            }
        }

        $temporaryUrlManager = $container
            ->getDefinition(TemporaryUrlResourceTransformer::class);

        $temporaryUrlManager->setArgument('$transformerMap', $transformerMap);
    }
}
