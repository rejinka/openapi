<?php
declare(strict_types = 1);

namespace NajiDevTests\Openapi\Parser\Parser\Info;

use NajiDev\JsonTree\Node;
use NajiDev\JsonTree\Nodes;
use NajiDev\Openapi\Model\Common\Url;
use NajiDev\Openapi\Model\Info\Contact;
use NajiDev\Openapi\Model\Info\Email;
use NajiDev\Openapi\Parser\Error\Error;
use NajiDev\Openapi\Parser\Error\UnexpectedNodeType;
use NajiDev\Openapi\Parser\Exception\NotParsableException;
use NajiDev\Openapi\Parser\Parser\Info\ContactParserImpl;
use NajiDev\Openapi\Parser\Path;
use NajiDev\Openapi\Parser\Test\ParserTestCase;

final class ContactParserImplTest extends ParserTestCase
{

    private ContactParserImpl $unit;

    protected function setUp(): void
    {
        $this->unit = new ContactParserImpl();
    }

    /**
     * @test
     * @dataProvider dataProviderFor_parsing_without_errors
     */
    public function parsing_without_errors(Nodes\ObjectNode $given, Contact $expected): void
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
            new Contact(
                'a name'
            )
        ];

        yield [
            new Nodes\ObjectNode(
                [
                    'name'  => new Nodes\StringNode('a name'),
                    'url'   => new Nodes\StringNode('https://example.com'),
                    'email' => new Nodes\StringNode('test@example.com'),
                ]
            ),
            new Contact(
                'a name',
                new Url('https://example.com'),
                new Email('test@example.com'),
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
            $rootNode = new Nodes\StringNode(''),
            new UnexpectedNodeType(Path::root(), [Nodes\ObjectNode::class], $rootNode),
        ];

        yield [
            new Nodes\ObjectNode(
                [
                    'name'  => $nameNode = new Nodes\NullNode(),
                ],
            ),
            new UnexpectedNodeType(Path::fromString('/name'), [Nodes\StringNode::class], $nameNode)
        ];

        yield [
            new Nodes\ObjectNode(
                [
                    'url'  => $urlNode = new Nodes\NullNode(),
                ],
            ),
            new UnexpectedNodeType(Path::fromString('/url'), [Nodes\StringNode::class], $urlNode)
        ];

        yield [
            new Nodes\ObjectNode(
                [
                    'email' => $emailNode = new Nodes\NullNode(),
                ],
            ),
            new UnexpectedNodeType(Path::fromString('/email'), [Nodes\StringNode::class], $emailNode)
        ];
    }

}
