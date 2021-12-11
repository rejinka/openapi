<?php
declare(strict_types = 1);

namespace NajiDev\JsonTree\Nodes;

use NajiDev\JsonTree\Node;

final class ListNode implements Node
{

    /**
     * @var list<Node>
     */
    private array $nodes;

    public function __construct(
        Node ...$nodes
    ) {
        $this->setNodes(...$nodes);
    }

    /**
     * @return list<Node>
     */
    public function getNodes(): array
    {
        return $this->nodes;
    }

    public function setNodes(Node ...$nodes): void
    {
        $this->nodes = $nodes;
    }

}
