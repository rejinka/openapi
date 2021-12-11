<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Model;

final class OpenApi
{

    public function __construct(
        private Info $info,
    ) {
    }

    public function getInfo(): Info
    {
        return $this->info;
    }

    public function setInfo(Info $info): void
    {
        $this->info = $info;
    }

}
