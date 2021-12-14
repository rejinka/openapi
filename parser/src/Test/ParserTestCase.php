<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Parser\Test;

use NajiDev\Openapi\Parser\Error\Error;
use NajiDev\Openapi\Parser\Exception\NotParsableException;
use PHPUnit\Framework\TestCase;

abstract class ParserTestCase extends TestCase
{

    protected function expectParsingError(callable $callable, Error ...$errors): void
    {
        try {
            call_user_func($callable);
        } catch (NotParsableException $exception) {
            self::assertEquals(
                $errors,
                $exception->getErrors()
            );
        }
    }

}
