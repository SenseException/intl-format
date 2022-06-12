<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use NumberFormatter;

use function is_numeric;
use function preg_match;

class PrecisionNumberFormatter implements FormatterInterface
{
    private static string $matchPattern = '/^([0-9]+)?\.?([0-9]*)number$/';

    public function __construct(private string $locale)
    {
    }

    public function formatValue(string $typeSpecifier, mixed $value): string
    {
        if (! is_numeric($value)) {
            throw InvalidValueException::invalidValueType($value, ['integer', 'double']);
        }

        preg_match(self::$matchPattern, $typeSpecifier, $matches);

        $paddingChar   = ' ';
        $paddingDigits = $matches[1];
        if (preg_match('/^0[0-9]+$/', $paddingDigits)) {
            $paddingChar = $paddingDigits[0];
        }

        $fractionalDigits = $matches[2];

        $formatter = new NumberFormatter($this->locale, NumberFormatter::DECIMAL);
        $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, (int) $fractionalDigits);
        $formatter->setAttribute(NumberFormatter::FORMAT_WIDTH, (int) $paddingDigits);
        $formatter->setTextAttribute(NumberFormatter::PADDING_CHARACTER, $paddingChar);

        /** @phpstan-var int|float $value */
        return (string) $formatter->format($value);
    }

    public function has(string $typeSpecifier): bool
    {
        return $typeSpecifier !== 'number' && $typeSpecifier !== '.number' &&
            preg_match(self::$matchPattern, $typeSpecifier) === 1;
    }
}
