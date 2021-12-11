<?php
declare(strict_types = 1);

namespace NajiDevTests\Openapi\Parser\Parser;

use NajiDev\JsonTree\Nodes;
use NajiDev\Openapi\Model;
use NajiDev\Openapi\Parser\Error\ExpectedMapNode;
use NajiDev\Openapi\Parser\Error\MissingProperty;
use NajiDev\Openapi\Parser\Exception\NotParsableException;
use NajiDev\Openapi\Parser\Parser\InfoParser;
use NajiDev\Openapi\Parser\Parser\OpenApiParserImpl;
use NajiDev\Openapi\Parser\Path;
use PHPUnit\Framework\TestCase;

final class OpenApiParserTestCase extends TestCase
{

    private InfoParser $infoParser;

    private OpenApiParserImpl $unit;

    protected function setUp(): void
    {
        parent::setUp();

        $this->unit = new OpenApiParserImpl(
            $this->infoParser = $this->createConfiguredMock(InfoParser::class, [
                '__invoke' => new Model\Info('', ''),
            ])
        );
    }

    /**
     * @test
     */
    public function cant_handle_integer(): void
    {
        $given = new Nodes\NullNode();

        $this->expectExceptionObject(
            new NotParsableException(
                new ExpectedMapNode(Path::root())
            )
        );

        $this->unit->__invoke($given);
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

        $expected = new Model\OpenApi(
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

        $this->expectExceptionObject(
            new NotParsableException(
                new MissingProperty(Path::fromString('/info'))
            )
        );

        $this->unit->__invoke($given);
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

        $this->expectExceptionObject(
            new NotParsableException(
                new MissingProperty(Path::fromString('/info/version')),
                new MissingProperty(Path::fromString('/info/title')),
            )
        );

        $this->unit->__invoke(new Nodes\ObjectNode());
    }

}
