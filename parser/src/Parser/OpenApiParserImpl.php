<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Parser\Parser;

use NajiDev\JsonTree\Node;
use NajiDev\JsonTree\Nodes\ObjectNode;
use NajiDev\Openapi\Model\OpenApi;
use NajiDev\Openapi\Parser\Error\MissingProperty;
use NajiDev\Openapi\Parser\Error\UnexpectedNodeType;
use NajiDev\Openapi\Parser\Exception\NotParsableException;
use NajiDev\Openapi\Parser\Parser\Info\InfoParser;
use NajiDev\Openapi\Parser\Path;

final class OpenApiParserImpl implements OpenApiParser
{

    public function __construct(
        private InfoParser $infoParser,
    ) {
    }

    public function __invoke(Node $node): OpenApi
    {
        if (!$node instanceof ObjectNode) {
            throw new NotParsableException(
                new UnexpectedNodeType(Path::root(), [ObjectNode::class], $node),
            );
        }

        $errors = new NotParsableException();

        try {
            $info = $this->infoParser->__invoke($node->get('info'));
        } catch (\OutOfBoundsException $e) {
            $errors->add(new MissingProperty(Path::fromString('/info')));
        } catch (NotParsableException $e) {
            $errors->catch($e, Path::fromString('/info'));
        }

        $errors->throwIfNotEmpty();

        assert(isset($info));

        return new OpenApi($info);
    }

}
