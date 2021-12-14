<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Parser\Parser\Info;

use NajiDev\JsonTree\Node;
use NajiDev\Openapi\Model\Info\Info;

interface InfoParser
{

    public function __invoke(Node $node): Info;

}
