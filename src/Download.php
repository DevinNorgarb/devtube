<?php

declare(strict_types=1);

namespace DevsWebDev\DevTube;

use Illuminate\Support\Collection;

class Download
{
    public function __construct(
        public ?string $url = null,
        public ?string $format = null,
        public ?string $path = null,
    ) {
    }

    /**
     * @return Collection<int, MediaFile>
     */
    public function download(): Collection
    {
        return app('devtube')->download($this->url, $this->format, $this->path);
    }
}
