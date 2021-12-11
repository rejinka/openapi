<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Parser\Error;

use NajiDev\Openapi\Parser\Exception\NotParsableException;
use NajiDev\Openapi\Parser\Path;

final class ErrorList
{

    /**
     * @var list<Error>
     */
    private array $errors = [];

    public function __construct(
        Error ...$errors
    ) {
        $this->errors = $errors;
    }

    public function add(Error $e): void
    {
        $this->errors[] = $e;
    }

    public function unexpectedType(Path $path = null): void
    {
        $path = $path ?: Path::root();

        $this->errors[] = new UnexpectedNodeType($path, '', '');
    }

    public function catch(NotParsableException $errorListException, Path $path = null): void
    {
        $path = $path ?: Path::root();

        foreach ($errorListException->getErrors() as $error) {
            $this->errors[] = $error->inPath(
                $path->append($error->getPath())
            );
        }
    }

    /**
     * @return Error[]
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function empty(): bool
    {
        return [] === $this->errors;
    }

    public function toException(): NotParsableException
    {
        return new NotParsableException(...$this->errors);
    }

}
