<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Parser\Error;

final class MissingProperty extends Error
{

    public function getDescription(): string
    {
        return 'Expected existing path ' . $this->getPath();
    }

}
