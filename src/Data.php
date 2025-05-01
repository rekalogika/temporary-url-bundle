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

/**
 * A resource object that can be used to generate a temporary URL. Serves as an
 * example on how to implement a resource.
 */
final class Data
{
    public function __construct(
        private readonly string $contentType,
        private readonly string $content,
        private readonly ?string $fileName,
    ) {}

    public function getContentType(): string
    {
        return $this->contentType;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getFileName(): ?string
    {
        return $this->fileName;
    }
}
