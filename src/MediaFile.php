<?php

declare(strict_types=1);

namespace DevsWebDev\DevTube;

use SplFileInfo;

class MediaFile
{
    public function __construct(
        public readonly ?string $title,
        public readonly ?SplFileInfo $file,
        public readonly ?string $error,
    ) {
    }

    public function wasSuccessful(): bool
    {
        return $this->error === null && $this->file !== null;
    }

    public function path(): ?string
    {
        return $this->file?->getPathname();
    }
}
