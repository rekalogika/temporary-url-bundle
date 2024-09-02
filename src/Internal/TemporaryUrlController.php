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

namespace Rekalogika\TemporaryUrl\Internal;

use Rekalogika\TemporaryUrl\Exception\WrongSessionException;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;

/**
 * The controller that handles temporary URL requests.
 *
 * @internal
 */
final class TemporaryUrlController
{
    public function __construct(
        private readonly TemporaryUrlManager $temporaryUrlManager,
        private readonly RequestStack $requestStack,
    ) {}

    public function __invoke(string $ticketid): Response
    {
        $temporaryUrlData = $this->temporaryUrlManager
            ->getTemporaryUrlDataFromTicketId($ticketid);

        if (($expectedSessionId = $temporaryUrlData->getSessionId()) !== null) {
            $currentSessionId = $this->requestStack->getSession()->getId();

            if ($currentSessionId !== $expectedSessionId) {
                throw new WrongSessionException(
                    $ticketid,
                    $expectedSessionId,
                );
            }
        }

        $callable = $this->temporaryUrlManager
            ->getCallableFromTemporaryUrlData($temporaryUrlData);
        $result = \call_user_func($callable, $temporaryUrlData->getResource());

        if (!$result instanceof Response) {
            throw new \UnexpectedValueException(sprintf(
                'The callable must return an instance of "%s", "%s" returned',
                Response::class,
                \is_object($result) ? \get_class($result) : \gettype($result),
            ));
        }

        return $result;
    }
}
