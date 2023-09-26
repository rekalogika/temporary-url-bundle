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

use Rekalogika\TemporaryUrl\DependencyInjection\TemporaryUrlPass;
use Rekalogika\TemporaryUrl\DependencyInjection\TemporaryUrlResourceTransformerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class RekalogikaTemporaryUrlBundle extends Bundle
{
    public function getPath(): string
    {
        return \dirname(__DIR__);
    }

    public function build(ContainerBuilder $container): void
    {
        parent::build($container);

        $container
            ->addCompilerPass(new TemporaryUrlPass())
            ->addCompilerPass(new TemporaryUrlResourceTransformerPass());
    }
}
