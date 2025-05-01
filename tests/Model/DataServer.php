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

namespace Rekalogika\TemporaryUrl\Tests\Model;

use Symfony\Component\HttpFoundation\Response;

final class DataServer
{
    public function serve(Data1|Data2 $data): Response
    {
        return new Response($data->getContent());
    }
}
