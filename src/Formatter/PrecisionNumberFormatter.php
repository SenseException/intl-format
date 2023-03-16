<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use NumberFormatter;

use function is_float;
use function is_int;
use function preg_match;
use function str_ends_with;

class PrecisionNumberFormatter implements FormatterInterface
{
    private static string $matchPattern = '/^([0-9]+)?\.?([0-9]*)number/';

    public function __construct(private string $locale)
    {
    }

    public function formatValue(string $typeSpecifier, mixed $value): string
    {
        if (! is_int($value) && ! is_float($value)) {
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

        $roundMode = $this->determineRoundMode($typeSpecifier);
        $formatter->setAttribute(NumberFormatter::ROUNDING_MODE, $roundMode);

        return (string) $formatter->format($value);
    }

    public function has(string $typeSpecifier): bool
    {
        return ($typeSpecifier !== 'number' && $typeSpecifier !== '.number' &&
            preg_match(self::$matchPattern, $typeSpecifier) === 1) &&
            (str_ends_with($typeSpecifier, 'number') || str_ends_with($typeSpecifier, 'number_halfway_up'));
    }

    private function determineRoundMode(string $typeSpecifier): int
    {
        return match (true) {
            str_ends_with($typeSpecifier, 'number_halfway_up') => NumberFormatter::ROUND_HALFUP,
            default => NumberFormatter::ROUND_HALFEVEN,
        };
    }
}
