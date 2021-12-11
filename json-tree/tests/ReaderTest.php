<?php
declare(strict_types = 1);

namespace NajiDevTests\JsonTree;

use NajiDev\JsonTree\Node;
use NajiDev\JsonTree\Nodes\BooleanNode;
use NajiDev\JsonTree\Nodes\NumberNode;
use NajiDev\JsonTree\Nodes\ListNode;
use NajiDev\JsonTree\Nodes\ObjectNode;
use NajiDev\JsonTree\Nodes\NullNode;
use NajiDev\JsonTree\Nodes\StringNode;
use NajiDev\JsonTree\Reader;
use PHPUnit\Framework\TestCase;

final class ReaderTest extends TestCase
{

    private Reader $reader;

    protected function setUp(): void
    {
        $this->reader = new Reader();
    }

    /**
     * @test
     * @dataProvider dataProvider
     *
     * @param string $json
     * @param Node   $expected
     * @return void
     */
    public function read(string $json, Node $expected): void
    {
        self::assertEquals(
            $expected,
            $this->reader->fromJson($json)
        );
    }

    public function dataProvider(): \Generator
    {
        yield [
            '5',
            new NumberNode(5),
        ];

        yield [
            '"5"',
            new StringNode('5'),
        ];

        yield [
            'false',
            new BooleanNode(false),
        ];

        yield [
            'true',
            new BooleanNode(true),
        ];

        yield [
            '2.1',
            new NumberNode(2.1)
        ];

        yield [
            '["5", 2]',
            new ListNode(
                new StringNode('5'),
                new NumberNode(2),
            )
        ];

        yield [
            'null',
            new NullNode()
        ];

        yield [
            '{"a": 1, "b": "2", "c": null}',
            new ObjectNode(
                [
                    'a' => new NumberNode(1),
                    'b' => new StringNode("2"),
                    'c' => new NullNode()
                ]
            )
        ];
    }

    /**
     * @test
     */
    public function read_yaml(): void
    {
        $given = <<<YAML
a:
  - 1
  - "2"
  - b: x
    c: 4
YAML;

        $expected = new ObjectNode(
            [
                'a' => new ListNode(
                    new NumberNode(1),
                    new StringNode('2'),
                    new ObjectNode(
                        [
                            'b' => new StringNode('x'),
                            'c' => new NumberNode(4)
                        ]
                    )
                )
            ]
        );

        self::assertEquals($expected, $this->reader->fromYaml($given));
    }

}
