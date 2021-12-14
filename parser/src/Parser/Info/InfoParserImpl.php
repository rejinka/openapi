<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Parser\Parser\Info;

use NajiDev\JsonTree\Node;
use NajiDev\JsonTree\Nodes\ObjectNode;
use NajiDev\Openapi\Model\Info\Info;
use NajiDev\Openapi\Parser\Exception\NotParsableException;
use NajiDev\Openapi\Parser\Parser\Helper;
use NajiDev\Openapi\Parser\Path;

final class InfoParserImpl implements InfoParser
{

    public function __construct(
        private ContactParser $contactParser,
        private LicenseParser $licenseParser,
    ) {
    }

    public function __invoke(Node $node): Info
    {
        if (!$node instanceof ObjectNode) {
            throw NotParsableException::expectedObjectNode($node);
        }

        $errors = new NotParsableException();

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
            $description = Helper::getStringFromOptionalProperty($node, 'description');
        } catch (NotParsableException $e) {
            $errors->catch($e);
        }

        try {
            $termsOfService = Helper::getUrlFromOptionalProperty($node, 'termsOfService');
        } catch (NotParsableException $e) {
            $errors->catch($e);
        }

        try {
            $contact = $this->contactParser->__invoke($node->get('contact'));
        } catch (\OutOfBoundsException $e) {
        } catch (NotParsableException $e) {
            $errors->catch($e, Path::fromString('/contact'));
        }

        try {
            $summary = Helper::getStringFromOptionalProperty($node, 'summary');
        } catch (NotParsableException $e) {
            $errors->catch($e);
        }

        try {
            $license = $this->licenseParser->__invoke($node->get('license'));
        } catch (\OutOfBoundsException $e) {
        } catch (NotParsableException $e) {
            $errors->catch($e, Path::fromString('/license'));
        }

        $errors->throwIfNotEmpty();

        assert(isset($title));
        assert(isset($version));

        return new Info(
            $title,
            $version,
            $summary ?? null,
            $description ?? null,
            $termsOfService ?? null,
            $contact ?? null,
            $license ?? null,
        );
    }

}
