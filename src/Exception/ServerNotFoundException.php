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

namespace Rekalogika\TemporaryUrl\Exception;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\WithHttpStatus;

#[WithHttpStatus(Response::HTTP_NOT_FOUND)]
class ServerNotFoundException extends TemporaryUrlException
{
    /**
     * @param class-string $resourceClass
     */
    public function __construct(
        string $resourceClass,
        \Throwable $previous = null,
    ) {
        $message = \sprintf('There is no server to serve the resource class "%s".', $resourceClass);

        parent::__construct($message, 0, $previous);
    }
}
