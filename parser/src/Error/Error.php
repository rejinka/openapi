<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Parser\Error;

use NajiDev\Openapi\Parser\Path;

abstract class Error
{

    public function __construct(
        private Path $path,
    ) {
    }

    public function getPath(): Path
    {
        return $this->path;
    }

    public function inPath(Path $path): self
    {
        $instance = clone $this;

        $instance->path = $path;

        return $instance;
    }

    abstract function getDescription(): string;

}
