<?php

/*
 * This file is part of rekalogika/temporary-url-bundle package.
 *
 * (c) Priyadi Iman Nurcahyo <https://rekalogika.dev>
 *
 * For the full copyright and license information, please view the LICENSE file
 * that was distributed with this source code.
 */

declare(strict_types=1);

use Rekalogika\TemporaryUrl\Internal\TemporaryUrlController;
use Symfony\Component\Routing\Loader\Configurator\RoutingConfigurator;

return static function (RoutingConfigurator $routes): void {
    $routes->add('rekalogika_temporary_url', '/{ticketid}')
        ->controller(TemporaryUrlController::class)
        ->methods(['GET']);
};
