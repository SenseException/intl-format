<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use Closure;
use Throwable;

class ExceptionFormatter implements FormatterInterface
{
    /** @var Closure[] */
    private array $formatFunctions;

    public function __construct()
    {
        $this->formatFunctions = [
            'emessage' => static fn (Throwable $throwable): string => $throwable->getMessage(),
            'ecode' => static fn (Throwable $throwable): string => (string) $throwable->getCode(),
            'efile' => static fn (Throwable $throwable): string => $throwable->getFile(),
            'eline' => static fn (Throwable $throwable): string => (string) $throwable->getLine(),
            'etrace' => static fn (Throwable $throwable): string => $throwable->getTraceAsString(),
        ];
    }

    public function formatValue(string $typeSpecifier, mixed $value): string
    {
        if (! $value instanceof Throwable) {
            throw InvalidValueException::invalidValueType($value, [Throwable::class]);
        }

        return (string) $this->formatFunctions[$typeSpecifier]($value);
    }

    public function has(string $typeSpecifier): bool
    {
        return isset($this->formatFunctions[$typeSpecifier]);
    }
}
