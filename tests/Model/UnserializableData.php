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

final class UnserializableData
{
    public function __construct(
        private readonly string $content,
    ) {}

    public function getContent(): string
    {
        return $this->content;
    }

    /**
     * @return array<array-key,mixed>
     */
    public function __serialize(): array
    {
        throw new \Exception('This class is not serializable.');
    }
}
