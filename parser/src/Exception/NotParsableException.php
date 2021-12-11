<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Parser\Exception;

use NajiDev\Openapi\Parser\Error\Error;

final class NotParsableException extends \InvalidArgumentException
{

    /**
     * @var list<Error>
     */
    private array $errors;

    public function __construct(
        Error ...$errors,
    ) {
        parent::__construct();

        $this->errors = $errors;
    }

    /**
     * @return list<Error>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

}
