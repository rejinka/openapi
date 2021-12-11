<?php
declare(strict_types = 1);

namespace NajiDev\JsonTree\Nodes;

use NajiDev\JsonTree\Node;

final class NumberNode implements Node
{

    public function __construct(
        public float|int $value
    ) {
    }

}
