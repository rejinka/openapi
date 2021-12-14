<?php
declare(strict_types = 1);

namespace NajiDevTests\Openapi\Parser\Parser;

use NajiDev\JsonTree\Nodes;
use NajiDev\Openapi\Model\Info;
use NajiDev\Openapi\Parser\Error\MissingProperty;
use NajiDev\Openapi\Parser\Error\UnexpectedNodeType;
use NajiDev\Openapi\Parser\Exception\NotParsableException;
use NajiDev\Openapi\Parser\Parser\Info\InfoParser;
use NajiDev\Openapi\Parser\Parser\OpenApiParserImpl;
use NajiDev\Openapi\Parser\Path;
use NajiDev\Openapi\Parser\Test\ParserTestCase;

final class OpenApiParserTest extends ParserTestCase
{

    private InfoParser $infoParser;

    private OpenApiParserImpl $unit;

    protected function setUp(): void
    {
        parent::setUp();

        $this->unit = new OpenApiParserImpl(
            $this->infoParser = $this->createConfiguredMock(InfoParser::class, [
                '__invoke' => new Info\Info('a title', '3.1.0'),
            ])
        );
    }

    /**
     * @test
     */
    public function cant_handle_integer(): void
    {
        $given = new Nodes\NullNode();

        $this->expectParsingError(
            fn () => $this->unit->__invoke($given),
            new UnexpectedNodeType(Path::root(), [Nodes\ObjectNode::class], $given),
        );
    }

    /**
     * @test
     */
    public function handle_minimal(): void
    {
        $given = new Nodes\ObjectNode(
            [
                'info' => new Nodes\ObjectNode(),
            ],
        );

        $expected = new \NajiDev\Openapi\Model\OpenApi(
            $this->infoParser->__invoke(new Nodes\NullNode()),
        );

        self::assertEquals($expected, $this->unit->__invoke($given));
    }

    /**
     * @test
     */
    public function info_required(): void
    {
        $given = new Nodes\ObjectNode();

        $this->expectParsingError(
            fn () => $this->unit->__invoke($given),
            new MissingProperty(Path::fromString('/info'))
        );
    }

    /**
     * @test
     */
    public function info_invalid(): void
    {
        $this->infoParser
            ->method('__invoke')
            ->willThrowException(
                new NotParsableException(
                    new MissingProperty(Path::fromString('/version')),
                    new MissingProperty(Path::fromString('/title')),
                )
            );

        $given = new Nodes\ObjectNode(
            [
                'info' => new Nodes\NullNode(),
            ],
        );

        $this->expectParsingError(
            fn () => $this->unit->__invoke($given),
            new MissingProperty(Path::fromString('/info/version')),
            new MissingProperty(Path::fromString('/info/title')),
        );
    }

}
