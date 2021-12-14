<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Model\Info;

use NajiDev\Openapi\Model\Common\Url;

final class Info
{

    public function __construct(
        private string       $title,
        private string       $version,
        private string|null  $summary = null,
        private string|null  $description = null,
        private Url|null     $termsOfService = null,
        private Contact|null $contact = null,
        private License|null $license = null,
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function getTermsOfService(): ?Url
    {
        return $this->termsOfService;
    }

    public function getContact(): ?Contact
    {
        return $this->contact;
    }

    public function getLicense(): ?License
    {
        return $this->license;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

}
