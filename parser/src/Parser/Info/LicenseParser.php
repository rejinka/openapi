<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Parser\Parser\Info;

use NajiDev\JsonTree\Node;
use NajiDev\Openapi\Model\Info\License;

interface LicenseParser
{

    public function __invoke(Node $node): License;

}
