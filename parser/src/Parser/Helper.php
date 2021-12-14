<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Parser\Parser;

use NajiDev\JsonTree\Node;
use NajiDev\JsonTree\Nodes\BooleanNode;
use NajiDev\JsonTree\Nodes\NullNode;
use NajiDev\JsonTree\Nodes\NumberNode;
use NajiDev\JsonTree\Nodes\ObjectNode;
use NajiDev\JsonTree\Nodes\StringNode;
use NajiDev\Openapi\Model\Common\Url;
use NajiDev\Openapi\Model\Info\Email;
use NajiDev\Openapi\Parser\Error\MissingProperty;
use NajiDev\Openapi\Parser\Error\UnexpectedNodeType;
use NajiDev\Openapi\Parser\Exception\NotParsableException;
use NajiDev\Openapi\Parser\Path;
use OutOfBoundsException;

final class Helper
{

    public static function getUrlFromOptionalProperty(ObjectNode $node, string $property): ?Url
    {
        $value = self::getStringFromOptionalProperty($node, $property);
        if (null === $value) {
            return null;
        }

        return new Url($value);
    }

    public static function getEmailFromOptionalProperty(ObjectNode $node, string $property): ?Email
    {
        $value = self::getStringFromOptionalProperty($node, $property);
        if (null === $value) {
            return null;
        }

        return new Email($value);
    }

    public static function getStringFromRequiredProperty(ObjectNode $node, string $property): string
    {
        $value = self::getScalarFromRequiredProperty($node, $property, [StringNode::class]);
        assert(is_string($value));

        return $value;
    }

    public static function getNullableStringFromRequiredProperty(ObjectNode $node, string $property): ?string
    {
        $value = self::getScalarFromRequiredProperty($node, $property, [StringNode::class, NullNode::class]);
        assert(null === $value || is_string($value));

        return $value;
    }

    public static function getStringFromOptionalProperty(ObjectNode $node, string $property): ?string
    {
        $value = self::getScalarFromOptionalProperty($node, $property, [StringNode::class]);
        assert(null === $value || is_string($value));

        return $value;
    }

    public static function getNullableStringFromOptionalProperty(ObjectNode $node, string $property): ?string
    {
        $value = self::getScalarFromOptionalProperty($node, $property, [StringNode::class, NullNode::class]);
        assert(null === $value || is_string($value));

        return $value;
    }

    /**
     * @param ObjectNode                $node
     * @param string                    $property
     * @param array<class-string<Node>> $types
     * @return int|float|bool|string|null
     */
    private static function getScalarFromOptionalProperty(ObjectNode $node, string $property, array $types): null|int|float|bool|string
    {
        if (!$node->has($property)) {
            return null;
        }

        return self::getScalarFromRequiredProperty($node, $property, $types);
    }

    /**
     * @param ObjectNode                $node
     * @param string                    $property
     * @param array<class-string<Node>> $types
     * @return int|float|bool|string|null
     */
    private static function getScalarFromRequiredProperty(ObjectNode $node, string $property, array $types): null|int|float|bool|string
    {
        $path = Path::fromString('/' . $property);

        try {
            $child = $node->get($property);
        } catch (OutOfBoundsException $_) {
            throw new NotParsableException(
                new MissingProperty($path),
            );
        }

        if (!in_array(get_class($child), $types, true)) {
            throw new NotParsableException(
                new UnexpectedNodeType(
                    $path,
                    $types,
                    $child
                )
            );
        }

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

        throw new NotParsableException(
            new UnexpectedNodeType($path, $types, $child)
        );
    }

}
