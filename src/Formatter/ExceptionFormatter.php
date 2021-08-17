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
            'emessage' => static function (Throwable $throwable): string {
                return $throwable->getMessage();
            },
            'ecode' => static function (Throwable $throwable): string {
                return (string) $throwable->getCode();
            },
            'efile' => static function (Throwable $throwable): string {
                return $throwable->getFile();
            },
            'eline' => static function (Throwable $throwable): string {
                return (string) $throwable->getLine();
            },
            'etrace' => static function (Throwable $throwable): string {
                return $throwable->getTraceAsString();
            },
        ];
    }

    /**
     * @inheritDoc
     */
    public function formatValue(string $typeSpecifier, $value): string
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
