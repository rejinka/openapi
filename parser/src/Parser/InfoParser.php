<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Parser\Parser;

use NajiDev\JsonTree\Node;
use NajiDev\Openapi\Model\Info;

interface InfoParser
{

    public function __invoke(Node $node): Info;

}
