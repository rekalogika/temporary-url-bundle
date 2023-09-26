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

namespace Rekalogika\TemporaryUrl\Tests;

use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel as HttpKernelKernel;
use Rekalogika\TemporaryUrl\RekalogikaTemporaryUrlBundle;

class Kernel extends HttpKernelKernel
{
    public function __construct()
    {
        parent::__construct('test', true);
    }

    public function registerBundles(): iterable
    {
        yield new RekalogikaTemporaryUrlBundle();
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
    }
}
