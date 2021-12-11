<?php
declare(strict_types = 1);

namespace NajiDev\JsonTree;

use NajiDev\JsonTree\Nodes\ListNode;
use NajiDev\JsonTree\Nodes\ObjectNode;
use Symfony\Component\Yaml\Yaml;

final class Reader
{

    public function fromJson(string $json): Node
    {
        $decoded = json_decode($json);

        return self::read($decoded);
    }

    public function fromYaml(string $yaml): Node
    {
        return self::read(
            Yaml::parse($yaml, Yaml::PARSE_OBJECT_FOR_MAP)
        );
    }

    private static function read(mixed $value): Node
    {
        if (null === $value) {
            return new Nodes\NullNode();
        }

        if (is_int($value) || is_float($value)) {
            return new Nodes\NumberNode($value);
        }

        if (is_string($value)) {
            return new Nodes\StringNode($value);
        }

        if (is_bool($value)) {
            return new Nodes\BooleanNode($value);
        }

        if (is_array($value)) {
            /** @var $value list<mixed> */
            return new ListNode(
                ...
                array_map(
                    fn (mixed $value) => self::read($value),
                    $value
                ),
            );
        }

        if ($value instanceof \stdClass) {
            return new ObjectNode(
                array_map(
                    fn (mixed $value) => self::read($value),
                    get_object_vars($value)
                )
            );
        }

        throw new \LogicException('Type not covered');
    }
}
