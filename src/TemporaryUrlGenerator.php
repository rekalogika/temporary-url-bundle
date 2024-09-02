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

namespace Rekalogika\TemporaryUrl;

use Rekalogika\TemporaryUrl\Internal\TemporaryUrlManager;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

final readonly class TemporaryUrlGenerator implements TemporaryUrlGeneratorInterface
{
    public function __construct(
        private TemporaryUrlManager $temporaryUrlManager,
        private UrlGeneratorInterface $urlGenerator,
        private RequestStack $requestStack,
    ) {
    }

    #[\Override]
    public function generateUrl(
        object $resource,
        null|int|\DateInterval $ttl = null,
        bool $pinSession = false,
        int $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH,
    ): string {
        $sessionId = $pinSession ? $this->requestStack->getSession()->getId() : null;

        $temporaryUrlResult = $this->temporaryUrlManager
            ->createTicket($resource, $ttl, $sessionId);

        return $this->urlGenerator->generate(
            'rekalogika_temporary_url',
            [
                'ticketid' => $temporaryUrlResult->getTicketId(),
                'expiration' => $temporaryUrlResult->getExpiration(),
            ],
            $referenceType,
        );
    }
}
