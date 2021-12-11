<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Parser\Parser;

use NajiDev\JsonTree\Node;
use NajiDev\JsonTree\Nodes\ObjectNode;
use NajiDev\Openapi\Model\Info;
use NajiDev\Openapi\Model\OpenApi;
use NajiDev\Openapi\Parser\Error\MissingProperty;
use NajiDev\Openapi\Parser\Exception\NotParsableException;
use NajiDev\Openapi\Parser\Error\ErrorList;
use NajiDev\Openapi\Parser\Path;

final class OpenApiParserImpl implements OpenApiParser
{

    public function __construct(
        private InfoParser $infoParser,
    ) {
    }

    public function __invoke(Node $node): OpenApi
    {
        $errors = new ErrorList();

        if (!$node instanceof ObjectNode) {
            $errors->unexpectedType(Path::root());

            throw $errors->toException();
        }

        try {
            $info = $this->infoParser->__invoke($node->get('info'));
        } catch (\OutOfBoundsException $e) {
            $errors->add(new MissingProperty(Path::fromString('/info')));
        } catch (NotParsableException $e) {
            $errors->catch($e, Path::fromString('/info'));
        }

        if (!$errors->empty()) {
            throw $errors->toException();
        }

        assert(null === $info || $info instanceof Info);

        return new OpenApi($info);
    }

}
