<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Parser\Parser;

use NajiDev\JsonTree\Node;
use NajiDev\JsonTree\Nodes\ObjectNode;
use NajiDev\Openapi\Model\Info;
use NajiDev\Openapi\Parser\Error\ErrorList;
use NajiDev\Openapi\Parser\Exception\NotParsableException;

final class InfoParserImpl implements InfoParser
{

    public function __invoke(Node $node): Info
    {
        $errors = new ErrorList();

        if (!$node instanceof ObjectNode) {
            $errors->unexpectedType();

            throw $errors->toException();
        }

        try {
            $title = Helper::getStringFromRequiredProperty($node, 'title');
        } catch (NotParsableException $e) {
            $errors->catch($e);
        }

        try {
            $version = Helper::getNullableStringFromRequiredProperty($node, 'version');
        } catch (NotParsableException $e) {
            $errors->catch($e);
        }

        try {
            $summary = Helper::getStringFromOptionalProperty($node, 'summary');
        } catch (NotParsableException $e) {
            $summary = null;

            $errors->catch($e);
        }

        if (!$errors->empty()) {
            throw $errors->toException();
        }

        assert(isset($title));
        assert(isset($version));

        $info = new Info(
            $title,
            $version,
        );

        $info->setSummary($summary);

        return $info;
    }

}
