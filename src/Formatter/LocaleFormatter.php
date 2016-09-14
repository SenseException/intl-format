<?php

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
     * @var array
     */
    private $formatFunctions;

    /**
     * @param string $locale
     */
    public function __construct($locale)
    {
        $this->locale = (string) $locale;
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
    public function formatValue($typeSpecifier, $value)
    {
        return $this->formatFunctions[$typeSpecifier]($value);;
    }

    /**
     * @inheritDoc
     */
    public function has($typeSpecifier)
    {
        return array_key_exists((string) $typeSpecifier, $this->formatFunctions);
    }
}