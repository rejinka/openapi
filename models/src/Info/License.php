<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Model\Info;


use NajiDev\Openapi\Model\Common\Url;

final class License
{

    public function __construct(
        private string $name,
        private string|null $identifier = null,
        private Url|null $url = null,
    ){
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getIdentifier(): ?string
    {
        return $this->identifier;
    }

    public function getUrl(): ?Url
    {
        return $this->url;
    }

}
