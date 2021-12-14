<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Model\Info;

use NajiDev\Openapi\Model\Common\Url;

final class Contact
{

    public function __construct(
        private string|null $name = null,
        private Url|null $url = null,
        private Email|null $email = null,
    ) {
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getUrl(): ?Url
    {
        return $this->url;
    }

    public function getEmail(): ?Email
    {
        return $this->email;
    }

}
