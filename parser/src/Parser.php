<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Parser;

use NajiDev\JsonTree\Node;
use NajiDev\Openapi\Model\OpenApi;
use NajiDev\Openapi\Parser\Parser\InfoParserImpl;
use NajiDev\Openapi\Parser\Parser\OpenApiParser;
use NajiDev\Openapi\Parser\Parser\OpenApiParserImpl;

final class Parser
{

    private OpenApiParser $parser;

    public function __construct()
    {
        $this->parser = new OpenApiParserImpl(
            new InfoParserImpl(),
        );
    }

    public function __invoke(Node $node): OpenApi
    {
        return $this->parser->__invoke($node);
    }

}
