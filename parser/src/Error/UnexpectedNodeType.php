<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Parser\Error;

use NajiDev\JsonTree\Node;
use NajiDev\JsonTree\Nodes\ObjectNode;
use NajiDev\Openapi\Parser\Path;

final class UnexpectedNodeType extends Error
{

    /**
     * @param Path                $path
     * @param array<class-string> $allowed
     * @param Node                $node
     */
    public function __construct(
        Path          $path,
        private array $allowed,
        private Node  $node,
    ) {
        parent::__construct($path);
    }

    public static function objectExpected(Path $path, Node $node): self
    {
        return new self($path, [ObjectNode::class], $node);
    }

    public function getAllowed(): array
    {
        return $this->allowed;
    }

    public function getNode(): Node
    {
        return $this->node;
    }

    public function getDescription(): string
    {
        return 'Expected to be type xy';
    }
}
