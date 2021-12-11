<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Parser\Parser;

use NajiDev\JsonTree\Nodes\BooleanNode;
use NajiDev\JsonTree\Nodes\NullNode;
use NajiDev\JsonTree\Nodes\NumberNode;
use NajiDev\JsonTree\Nodes\ObjectNode;
use NajiDev\JsonTree\Nodes\StringNode;
use NajiDev\Openapi\Parser\Error\ErrorList;
use NajiDev\Openapi\Parser\Error\MissingProperty;
use NajiDev\Openapi\Parser\Error\UnexpectedNodeType;
use NajiDev\Openapi\Parser\Path;
use OutOfBoundsException;

final class Helper
{

    public static function getStringFromRequiredProperty(ObjectNode $node, string $property): string
    {
        $value = self::getScalarFromRequiredProperty($node, $property, ['string']);
        assert(is_string($value));

        return $value;
    }

    public static function getNullableStringFromRequiredProperty(ObjectNode $node, string $property): ?string
    {
        $value = self::getScalarFromRequiredProperty($node, $property, ['string', 'NULL']);
        assert(null === $value || is_string($value));

        return $value;
    }

    public static function getStringFromOptionalProperty(ObjectNode $node, string $property): ?string
    {
        $value = self::getScalarFromOptionalProperty($node, $property, ['string']);
        assert(null === $value || is_string($value));

        return $value;
    }

    public static function getNullableStringFromOptionalProperty(ObjectNode $node, string $property): ?string
    {
        $value = self::getScalarFromOptionalProperty($node, $property, ['string', 'NULL']);
        assert(null === $value || is_string($value));

        return $value;
    }

    private static function getScalarFromOptionalProperty(ObjectNode $node, string $property, array $types): null|int|float|bool|string
    {
        if (!$node->has($property)) {
            return null;
        }

        return self::getScalarFromRequiredProperty($node, $property, $types);
    }

    private static function getScalarFromRequiredProperty(ObjectNode $node, string $property, array $types): null|int|float|bool|string
    {
        $path = Path::fromString('/' . $property);

        try {
            $child = $node->get($property);
        } catch (OutOfBoundsException $_) {
            throw (new ErrorList(
                new MissingProperty($path)
            ))->toException();
        }

        $value = (function () use ($child) {
            if ($child instanceof NullNode) {
                return null;
            }

            if ($child instanceof NumberNode) {
                return $child->value;
            }

            if ($child instanceof BooleanNode) {
                return $child->value;
            }

            if ($child instanceof StringNode) {
                return $child->value;
            }

            return new \stdClass();
        })();

        if (!in_array(gettype($value), $types, true)) {
            throw (new ErrorList(
                new UnexpectedNodeType(
                    $path,
                    'xy',
                    $value
                )
            ))->toException();
        }

        return $value;
    }

}
