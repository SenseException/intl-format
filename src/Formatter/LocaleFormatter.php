<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use Locale;
use Override;

use function is_string;

class LocaleFormatter implements FormatterInterface
{
    /** @phpstan-var array<callable(string $value): string> */
    private array $formatFunctions;

    public function __construct(private string $locale)
    {
        $this->formatFunctions = [
            'language' => $this->formatLanguage(...),
            'region' => $this->formatRegion(...),
        ];
    }

    #[Override]
    public function formatValue(string $typeSpecifier, mixed $value): string
    {
        if (! is_string($value)) {
            throw InvalidValueException::invalidValueType($value, ['string']);
        }

        return $this->formatFunctions[$typeSpecifier]($value);
    }

    #[Override]
    public function has(string $typeSpecifier): bool
    {
        return isset($this->formatFunctions[$typeSpecifier]);
    }

    private function formatLanguage(string $value): string
    {
        $language = Locale::getDisplayLanguage($value, $this->locale);

        if ($value === $language || $language === false) {
            throw InvalidValueException::invalidLocale($value);
        }

        return $language;
    }

    private function formatRegion(string $value): string
    {
        $region = Locale::getDisplayRegion($value, $this->locale);

        if ($region === '' || $region === false) {
            throw InvalidValueException::invalidLocale($value);
        }

        return $region;
    }
}
