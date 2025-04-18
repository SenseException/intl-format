<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use NumberFormatter;
use Override;

use function assert;
use function count;
use function in_array;
use function is_float;
use function is_int;
use function is_numeric;
use function is_string;
use function preg_match;
use function str_ends_with;

class PrecisionNumberFormatter implements FormatterInterface
{
    private const MATCH_PATTERN = '/^([\d]+)?\.?([\d]*)(number[\w_]*)/';

    public function __construct(private string $locale)
    {
    }

    #[Override]
    public function formatValue(string $typeSpecifier, mixed $value): string
    {
        if (! is_int($value) && ! is_float($value)) {
            throw InvalidValueException::invalidValueType($value, ['integer', 'double']);
        }

        preg_match(self::MATCH_PATTERN, $typeSpecifier, $matches);

        assert(count($matches) > 2);

        $paddingChar   = ' ';
        $paddingDigits = $matches[1];
        if (is_numeric($paddingDigits) && preg_match('/0[0-9]+/', $paddingDigits) === 1) {
            $paddingChar = $paddingDigits[0];
        }

        $fractionalDigits = $matches[2];

        $formatter = new NumberFormatter($this->locale, NumberFormatter::DECIMAL);
        $formatter->setAttribute(NumberFormatter::FRACTION_DIGITS, (int) $fractionalDigits);
        $formatter->setAttribute(NumberFormatter::FORMAT_WIDTH, (int) $paddingDigits);
        $formatter->setTextAttribute(NumberFormatter::PADDING_CHARACTER, $paddingChar);

        $roundMode = $this->determineRoundMode($typeSpecifier);
        $formatter->setAttribute(NumberFormatter::ROUNDING_MODE, $roundMode);

        $formattedValue = $formatter->format($value);

        assert(is_string($formattedValue));

        return $formattedValue;
    }

    #[Override]
    public function has(string $typeSpecifier): bool
    {
        $suffixes = [
            'number',
            'number_ceil',
            'number_halfway_up',
            'number_floor',
            'number_halfway_down',
            'number_halfeven',
        ];

        if ($typeSpecifier === 'number' || $typeSpecifier === '.number') {
            return false;
        }

        $patternFound = preg_match(self::MATCH_PATTERN, $typeSpecifier, $matches) === 1;

        return $patternFound && in_array($matches[3] ?? '', $suffixes, true);
    }

    private function determineRoundMode(string $typeSpecifier): int
    {
        return match (true) {
            str_ends_with($typeSpecifier, 'number_halfway_up') => NumberFormatter::ROUND_HALFUP,
            str_ends_with($typeSpecifier, 'number_halfway_down') => NumberFormatter::ROUND_HALFDOWN,
            str_ends_with($typeSpecifier, 'number_ceil') => NumberFormatter::ROUND_CEILING,
            str_ends_with($typeSpecifier, 'number_floor') => NumberFormatter::ROUND_FLOOR,
            default => NumberFormatter::ROUND_HALFEVEN,
        };
    }
}
