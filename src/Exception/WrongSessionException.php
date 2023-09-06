<?php

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
class WrongSessionException extends TemporaryUrlException
{
    public function __construct(
        string $ticketId,
        string $sessionId,
        \Throwable $previous = null,
    ) {
        $message = sprintf('The ticket "%s" is only valid with session ID "%s"', $ticketId, $sessionId);

        parent::__construct($message, 0, $previous);
    }
}
