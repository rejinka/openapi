<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Parser\Parser\Info;

use NajiDev\JsonTree\Node;
use NajiDev\JsonTree\Nodes\ObjectNode;
use NajiDev\Openapi\Model\Info\Contact;
use NajiDev\Openapi\Parser\Exception\NotParsableException;
use NajiDev\Openapi\Parser\Parser\Helper;

final class ContactParserImpl implements ContactParser
{

    public function __invoke(Node $node): Contact
    {
        if (!$node instanceof ObjectNode) {
            throw NotParsableException::expectedObjectNode($node);
        }

        $errors = new NotParsableException();

        try {
            $name = Helper::getStringFromOptionalProperty($node, 'name');
        } catch (NotParsableException $e) {
            $errors->catch($e);
        }

        try {
            $url = Helper::getUrlFromOptionalProperty($node, 'url');
        } catch (NotParsableException $e) {
            $errors->catch($e);
        }

        try {
            $email = Helper::getEmailFromOptionalProperty($node, 'email');
        } catch (NotParsableException $e) {
            $errors->catch($e);
        }

        $errors->throwIfNotEmpty();

        return new Contact(
            $name ?? null,
            $url ?? null,
            $email ?? null,
        );
    }

}
