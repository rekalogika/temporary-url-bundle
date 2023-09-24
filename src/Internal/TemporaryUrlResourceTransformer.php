<?php

/*
 * This file is part of rekalogika/temporary-url-bundle package.
 *
 * (c) Priyadi Iman Nurcahyo <https://rekalogika.dev>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Rekalogika\TemporaryUrl\Internal;

/**
 * Transforms a resource into a serializable form.
 * 
 * @internal
 */
class TemporaryUrlResourceTransformer
{
    /**
     * @var array<class-string,array{0:object,1:string}>
     */
    private array $transformerMap;

    /**
     * @param iterable<class-string,array{0:object,1:string}> $transformerMap
     */
    public function __construct(
        iterable $transformerMap,
    ) {
        if ($transformerMap instanceof \Traversable) {
            $transformerMap = iterator_to_array($transformerMap);
        }

        $this->transformerMap = $transformerMap;
    }
    
    public function transform(object $input): object
    {
        foreach ($this->transformerMap as $class => $transformer) {
            if ($input instanceof $class) {
                if (!is_callable($transformer)) {
                    throw new \RuntimeException(sprintf('Invalid transformer: transformer for class "%s" is not callable.', $class));
                }

                /** @var object */
                $result = \call_user_func($transformer, $input);
                return $result;
            }
        }

        return $input;
    }
}
