<?php
declare(strict_types = 1);

namespace NajiDev\JsonTree\Nodes;

use NajiDev\JsonTree\Node;
use OutOfBoundsException;

final class ObjectNode implements Node
{

    /**
     * @var array<string, Node>
     */
    private array $map;

    /**
     * @param array<string, Node> $map
     */
    public function __construct(
        array $map = [],
    ) {
        $this->map = [];

        foreach ($map as $key => $value) {
            $this->set($key, $value);
        }
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->map);
    }

    public function get(string $key): ?Node
    {
        return $this->map[$key] ?? throw new OutOfBoundsException();
    }

    public function set(string $key, Node $value): void
    {
        $this->map[$key] = $value;
    }

    public function remove(string $key): void
    {
        unset($this->map[$key]);
    }

}
