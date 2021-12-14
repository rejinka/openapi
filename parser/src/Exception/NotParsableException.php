<?php
declare(strict_types = 1);

namespace NajiDev\Openapi\Parser\Exception;

use NajiDev\JsonTree\Node;
use NajiDev\JsonTree\Nodes\ObjectNode;
use NajiDev\Openapi\Parser\Error\Error;
use NajiDev\Openapi\Parser\Error\UnexpectedNodeType;
use NajiDev\Openapi\Parser\Path;

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

        $this->errors = array_values($errors);
    }

    public static function expectedObjectNode(Node $node, Path $path = null): self
    {
        return new self(
            new UnexpectedNodeType($path ?: Path::root(), [ObjectNode::class], $node)
        );
    }

    public function add(Error $e): void
    {
        $this->errors[] = $e;
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
     * @return list<Error>
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function empty(): bool
    {
        return [] === $this->errors;
    }

    public function throwIfNotEmpty(): void
    {
        if ($this->empty()) {
            return;
        }

        throw $this;
    }

}
