<?php

/*
 * This file is part of rekalogika/temporary-url-bundle package.
 *
 * (c) Priyadi Iman Nurcahyo <https://rekalogika.dev>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

namespace Rekalogika\TemporaryUrl\Tests\Model;

use Rekalogika\TemporaryUrl\Attribute\AsTemporaryUrlResourceTransformer;
use Rekalogika\TemporaryUrl\Attribute\AsTemporaryUrlServer;
use Symfony\Component\HttpFoundation\Response;

class UnserializableDataServer
{
    #[AsTemporaryUrlServer]
    public function serve(TransformedUnserializableData $data): Response
    {
        return new Response($data->getContent());
    }

    #[AsTemporaryUrlResourceTransformer]
    public function transform(UnserializableData $data): TransformedUnserializableData
    {
        return new TransformedUnserializableData($data->getContent());
    }
}
