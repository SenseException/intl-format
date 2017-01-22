<?php
declare(strict_types = 1);

namespace Budgegeria\IntlFormat\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use Locale;

class LocaleFormatter implements FormatterInterface
{
    /**
     * @var string
     */
    private $locale;

    /**
     * @var \Closure[]
     */
    private $formatFunctions;

    /**
     * @param string $locale
     */
    public function __construct(string $locale)
    {
        $this->locale = $locale;
        $this->formatFunctions = [
            'language' => function($value) use ($locale) {
                $language = Locale::getDisplayLanguage($value, $locale);

                if ($value === $language) {
                    throw InvalidValueException::invalidLocale($value);
                }

                return $language;
            },
            'region' => function($value) use ($locale) {
                $region = Locale::getDisplayRegion($value, $locale);

                if ('' === $region) {
                    throw InvalidValueException::invalidLocale($value);
                }

                return $region;
            },
        ];
    }

    /**
     * @inheritDoc
     */
    public function formatValue(string $typeSpecifier, $value) : string
    {
        return $this->formatFunctions[$typeSpecifier]($value);
    }

    /**
     * @inheritDoc
     */
    public function has(string $typeSpecifier) : bool
    {
        return array_key_exists((string) $typeSpecifier, $this->formatFunctions);
    }
}
