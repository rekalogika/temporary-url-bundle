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
class TicketNotFoundException extends TemporaryUrlException
{
    public function __construct(
        string $ticketId,
        ?\Throwable $previous = null,
    ) {
        $message = \sprintf('Ticket with id "%s" not found', $ticketId);

        parent::__construct($message, 0, $previous);
    }
}
