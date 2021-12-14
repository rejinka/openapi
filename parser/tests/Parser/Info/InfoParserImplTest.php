<?php
declare(strict_types = 1);

namespace NajiDevTests\Openapi\Parser\Parser\Info;

use NajiDev\JsonTree\Node;
use NajiDev\JsonTree\Nodes;
use NajiDev\Openapi\Model\Common\Url;
use NajiDev\Openapi\Model\Info;
use NajiDev\Openapi\Model\Info\License;
use NajiDev\Openapi\Parser\Parser\Info\ContactParser;
use NajiDev\Openapi\Parser\Parser\Info\InfoParserImpl;
use NajiDev\Openapi\Parser\Parser\Info\LicenseParser;
use PHPUnit\Framework\TestCase;

final class InfoParserImplTest extends TestCase
{

    private ContactParser $contactParser;

    private LicenseParser $licenseParser;

    private InfoParserImpl $unit;

    protected function setUp(): void
    {
        parent::setUp();

        $this->contactParser = $this->createMock(ContactParser::class);
        $this->licenseParser = $this->createMock(LicenseParser::class);

        $this->unit = new InfoParserImpl(
            $this->contactParser,
            $this->licenseParser,
        );
    }

    /**
     * @test
     * @dataProvider dataProviderFor_parsing_without_errors
     */
    public function parsing_without_errors(Nodes\ObjectNode $given, Info\Info $expected): void
    {
        self::assertEquals($expected, $this->unit->__invoke($given));
    }

    public function dataProviderFor_parsing_without_errors(): \Generator
    {
        yield [
            new Nodes\ObjectNode(
                [
                    'title'   => new Nodes\StringNode('a title'),
                    'version' => new Nodes\StringNode('3.1.0'),
                ],
            ),
            new Info\Info(
                'a title',
                '3.1.0',
            )
        ];

        yield [
            new Nodes\ObjectNode(
                [
                    'title'          => new Nodes\StringNode('a title'),
                    'summary'        => new Nodes\StringNode('summary'),
                    'description'    => new Nodes\StringNode('description'),
                    'termsOfService' => new Nodes\StringNode('https://example.com'),
                    'version'        => new Nodes\StringNode('3.1.0'),
                ]
            ),
            new Info\Info(
                'a title',
                '3.1.0',
                'summary',
                'description',
                new Url('https://example.com'),
            )
        ];
    }

    /**
     * @test
     */
    public function contact_parsed(): void
    {
        $contactNode  = $this->createMock(Node::class);
        $contactModel = new Info\Contact();

        $this->contactParser
            ->expects(self::once())
            ->method('__invoke')
            ->with($contactNode)
            ->willReturn($contactModel);

        $given = new Nodes\ObjectNode(
            [
                'title'          => new Nodes\StringNode('a title'),
                'contact'        => $contactNode,
                'version'        => new Nodes\StringNode('3.1.0'),
            ]
        );

        $expected = new Info\Info(
            'a title',
            '3.1.0',
            contact: $contactModel,
        );

        self::assertEquals($expected, $this->unit->__invoke($given));
    }

    /**
     * @test
     */
    public function license_parsed(): void
    {
        $licenseNode  = $this->createMock(Node::class);
        $licenseModel = new License('license');

        $this->licenseParser
            ->expects(self::once())
            ->method('__invoke')
            ->with($licenseNode)
            ->willReturn($licenseModel);

        $given = new Nodes\ObjectNode(
            [
                'title'          => new Nodes\StringNode('a title'),
                'license'        => $licenseNode,
                'version'        => new Nodes\StringNode('3.1.0'),
            ]
        );

        $expected = new Info\Info(
            'a title',
            '3.1.0',
            license: $licenseModel,
        );

        self::assertEquals($expected, $this->unit->__invoke($given));
    }

}
