<?php
declare(strict_types = 1);

namespace NajiDevTests\Openapi\Parser\Parser;

use NajiDev\JsonTree\Nodes;
use NajiDev\Openapi\Model;
use NajiDev\Openapi\Parser\Error\ExpectedMapNode;
use NajiDev\Openapi\Parser\Error\MissingProperty;
use NajiDev\Openapi\Parser\Exception\NotParsableException;
use NajiDev\Openapi\Parser\Parser\InfoParserImpl;
use NajiDev\Openapi\Parser\Path;
use PHPUnit\Framework\TestCase;

final class InfoParserTestCase extends TestCase
{

    private InfoParserImpl $unit;

    protected function setUp(): void
    {
        parent::setUp();

        $this->unit = new InfoParserImpl();
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
                'title'   => new Nodes\StringNode('a title'),
                'version' => new Nodes\StringNode('a version'),
            ],
        );

        $expected = new Model\Info(
            'a title',
            'a version'
        );

        self::assertEquals($expected, $this->unit->__invoke($given));
    }

    /**
     * @test
     */
    public function required(): void
    {
        $given = new Nodes\ObjectNode();

        $this->expectExceptionObject(
            new NotParsableException(
                new MissingProperty(Path::fromString('/info')),
                new MissingProperty(Path::fromString('/title')),
            ),
        );

        $this->unit->__invoke($given);
    }

    public function optional(): void
    {

    }

}
