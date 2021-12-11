<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Parser\Error;

use NajiDev\Openapi\Parser\Path;

final class UnexpectedNodeType extends Error
{

    public function __construct(
        Path $path,
        private string $desired,
        private mixed $value,
    ) {
        parent::__construct($path);
    }

    public function getDesired(): string
    {
        return $this->desired;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    function getDescription(): string
    {
        return 'Expected to be type xy';
    }
}
