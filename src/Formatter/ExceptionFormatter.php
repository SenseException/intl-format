<?php
declare(strict_types = 1);

namespace Budgegeria\IntlFormat\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use Throwable;

class ExceptionFormatter implements FormatterInterface
{
    /**
     * @var \Closure[]
     */
    private $formatFunctions;

    public function __construct()
    {
        $this->formatFunctions = [
            'emessage' => function(Throwable $throwable) {
                return $throwable->getMessage();
            },
            'ecode' => function(Throwable $throwable) {
                return $throwable->getCode();
            },
            'efile' => function(Throwable $throwable) {
                return $throwable->getFile();
            },
            'eline' => function(Throwable $throwable) {
                return $throwable->getLine();
            },
            'etrace' => function(Throwable $throwable) {
                return $throwable->getTraceAsString();
            },
        ];
    }

    /**
     * @inheritDoc
     */
    public function formatValue(string $typeSpecifier, $value) : string
    {
        if (!$value instanceof Throwable) {
            throw InvalidValueException::invalidValueType($value, [Throwable::class]);
        }

        return (string) $this->formatFunctions[$typeSpecifier]($value);
    }

    /**
     * @inheritDoc
     */
    public function has(string $typeSpecifier) : bool
    {
        return isset($this->formatFunctions[$typeSpecifier]);
    }
}