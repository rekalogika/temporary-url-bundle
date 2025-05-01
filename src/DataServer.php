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

use Rekalogika\TemporaryUrl\Attribute\AsTemporaryUrlServer;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Symfony\Component\HttpFoundation\Response;

/**
 * Turns a Data object into a HTTP response. Serves as an example on how to
 * implement a temporary URL server.
 */
final class DataServer
{
    #[AsTemporaryUrlServer]
    public function respond(Data $dataResource): Response
    {
        $response = new Response();
        $response->headers->set('Content-Type', $dataResource->getContentType());

        $fileName = $dataResource->getFileName();

        if ($fileName !== null) {
            $disposition = HeaderUtils::makeDisposition(
                HeaderUtils::DISPOSITION_ATTACHMENT,
                $fileName,
            );

            $response->headers->set('Content-Disposition', $disposition);
        }

        $response->setContent($dataResource->getContent());

        return $response;
    }
}
