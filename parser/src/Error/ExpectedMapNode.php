<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Parser\Error;

final class ExpectedMapNode extends Error
{

    public function getDescription(): string
    {
        return 'Expected an object at ' . $this->getPath();
    }
}
