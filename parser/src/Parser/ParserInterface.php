<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Parser\Parser;

use NajiDev\JsonTree\Node;

interface ParserInterface
{

    public function __invoke(Node $node): mixed;

}
