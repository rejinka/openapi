<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Parser\Parser\Info;


use NajiDev\JsonTree\Node;
use NajiDev\JsonTree\Nodes\ObjectNode;
use NajiDev\Openapi\Model\Info\License;
use NajiDev\Openapi\Parser\Exception\NotParsableException;
use NajiDev\Openapi\Parser\Parser\Helper;

final class LicenseParserImpl implements LicenseParser
{

    public function __invoke(Node $node): License
    {

        if (!$node instanceof ObjectNode) {
            throw NotParsableException::expectedObjectNode($node);
        }

        $errors = new NotParsableException();

        try {
            $name = Helper::getStringFromRequiredProperty($node, 'name');
        } catch (NotParsableException $e) {
            $errors->catch($e);
        }

        try {
            $identifier = Helper::getStringFromOptionalProperty($node, 'identifier');
        } catch (NotParsableException $e) {
            $errors->catch($e);
        }

        try {
            $url = Helper::getUrlFromOptionalProperty($node, 'url');
        } catch (NotParsableException $e) {
            $errors->catch($e);
        }

        $errors->throwIfNotEmpty();

        assert(isset($name));

        return new License(
            $name,
            $identifier ?? null,
            $url ?? null,
        );
    }

}
