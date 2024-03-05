<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use Override;

use function is_float;
use function is_int;
use function is_string;
use function preg_match;
use function sprintf;

class SprintfFormatter implements FormatterInterface
{
    #[Override]
    public function formatValue(string $typeSpecifier, mixed $value): string
    {
        if (! (is_string($value) || is_int($value) || is_float($value))) {
            throw InvalidValueException::invalidValueType($value, ['float', 'int', 'string']);
        }

        return sprintf('%' . $typeSpecifier, $value);
    }

    #[Override]
    public function has(string $typeSpecifier): bool
    {
        return (bool) preg_match('/[+\-]?(\'?.)?(?:--)?\d*(?:\.?\d*)[%bcdeEfFgGosuxX]/', $typeSpecifier);
    }
}
