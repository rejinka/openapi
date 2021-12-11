<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Parser;


final class Path
{

    public static function fromString(string $value): self
    {
        return new self($value);
    }

    public static function root(): self
    {
        return new self('/');
    }

    public function append(self $other): self
    {
        if (self::root()->equals($this)) {
            return $other;
        }

        if (self::root()->equals($other)) {
            return $this;
        }

        return new self(
            $this->value . $other->value
        );
    }

    public function __toString(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $other->value === $this->value;
    }

    private function __construct(private string $value)
    {
        if ('/' === $this->value) {
            return;
        }

        if (!str_starts_with($this->value, '/')) {
            throw new \InvalidArgumentException('Invalid path,');
        }

        if (str_ends_with($this->value, '/')) {
            throw new \InvalidArgumentException('Invalid path.');
        }
    }

}
