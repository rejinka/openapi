<?php
declare(strict_types = 1);

namespace NajiDevTests\Openapi\Parser\Parser\Info;

use NajiDev\JsonTree\Node;
use NajiDev\JsonTree\Nodes;
use NajiDev\Openapi\Model\Common\Url;
use NajiDev\Openapi\Model\Info\License;
use NajiDev\Openapi\Parser\Error\Error;
use NajiDev\Openapi\Parser\Error\MissingProperty;
use NajiDev\Openapi\Parser\Error\UnexpectedNodeType;
use NajiDev\Openapi\Parser\Parser\Info\LicenseParserImpl;
use NajiDev\Openapi\Parser\Path;
use NajiDev\Openapi\Parser\Test\ParserTestCase;

final class LicenseParserImplTest extends ParserTestCase
{

    private LicenseParserImpl $unit;

    protected function setUp(): void
    {
        $this->unit = new LicenseParserImpl();
    }

    /**
     * @test
     * @dataProvider dataProviderFor_parsing_without_errors
     */
    public function parsing_without_errors(Nodes\ObjectNode $given, License $expected): void
    {
        self::assertEquals($expected, $this->unit->__invoke($given));
    }

    public function dataProviderFor_parsing_without_errors(): \Generator
    {
        yield [
            new Nodes\ObjectNode(
                [
                    'name' => new Nodes\StringNode('a name'),
                ]
            ),
            new License(
                'a name'
            )
        ];

        yield [
            new Nodes\ObjectNode(
                [
                    'name'       => new Nodes\StringNode('a name'),
                    'identifier' => new Nodes\StringNode('an identifier'),
                    'url'        => new Nodes\StringNode('https://example.com'),
                ]
            ),
            new License(
                'a name',
                'an identifier',
                new Url('https://example.com')
            )
        ];
    }

    /**
     * @test
     * @dataProvider dataProviderFor_parsing_with_errors
     */
    public function parsing_with_errors(Node $given, Error ...$expected): void
    {
        $this->expectParsingError(
            fn () => $this->unit->__invoke($given),
            ...$expected
        );
    }

    public function dataProviderFor_parsing_with_errors(): \Generator
    {
        yield [
            $rootNode = new Nodes\NullNode(),
            new UnexpectedNodeType(Path::root(), [Nodes\ObjectNode::class], $rootNode),
        ];

        yield [
            new Nodes\ObjectNode(),
            new MissingProperty(Path::fromString('/name'))
        ];

        yield [
            new Nodes\ObjectNode(
                [
                    'name'       => new Nodes\StringNode('a name'),
                    'identifier' => $identifierNode = new Nodes\NullNode(),
                ],
            ),
            new UnexpectedNodeType(Path::fromString('/identifier'), [Nodes\StringNode::class], $identifierNode)
        ];

        yield [
            new Nodes\ObjectNode(
                [
                    'name' => new Nodes\StringNode('a name'),
                    'url'  => $urlNode = new Nodes\NullNode(),
                ],
            ),
            new UnexpectedNodeType(Path::fromString('/url'), [Nodes\StringNode::class], $urlNode)
        ];
    }

}
