<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use Locale;

use function is_string;

class LocaleFormatter implements FormatterInterface
{
    /** @phpstan-var array<callable(string $value): string> */
    private array $formatFunctions;

    public function __construct(string $locale)
    {
        $this->formatFunctions = [
            'language' => static function (string $value) use ($locale): string {
                $language = Locale::getDisplayLanguage($value, $locale);

                if ($value === $language) {
                    throw InvalidValueException::invalidLocale($value);
                }

                return $language;
            },
            'region' => static function (string $value) use ($locale): string {
                $region = Locale::getDisplayRegion($value, $locale);

                if ($region === '') {
                    throw InvalidValueException::invalidLocale($value);
                }

                return $region;
            },
        ];
    }

    public function formatValue(string $typeSpecifier, mixed $value): string
    {
        if (! is_string($value)) {
            throw InvalidValueException::invalidValueType($value, ['string']);
        }

        return $this->formatFunctions[$typeSpecifier]($value);
    }

    public function has(string $typeSpecifier): bool
    {
        return isset($this->formatFunctions[$typeSpecifier]);
    }
}
