<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Model;


final class Info
{

    private string $title;

    private string $version;

    private ?string $summary = null;

    public function __construct(
        string $title,
        string $version,
    ) {
        $this->setTitle($title);
        $this->setVersion($version);
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): void
    {
        $this->title = $title;
    }

    public function getVersion(): string
    {
        return $this->version;
    }

    public function setVersion(string $version): void
    {
        $this->version = $version;
    }

    public function getSummary(): ?string
    {
        return $this->summary;
    }

    public function setSummary(?string $summary): void
    {
        $this->summary = $summary;
    }


}
