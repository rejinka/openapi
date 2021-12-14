<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Model;

final class OpenApi
{

    public function __construct(
        private Info\Info $info,
    ) {
    }

    public function getInfo(): Info\Info
    {
        return $this->info;
    }

}
