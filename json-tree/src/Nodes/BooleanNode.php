<?php
declare(strict_types = 1);

namespace NajiDev\JsonTree\Nodes;

use NajiDev\JsonTree\Node;

final class BooleanNode implements Node
{

    public function __construct(
        public bool $value,
    ) {
    }

}
