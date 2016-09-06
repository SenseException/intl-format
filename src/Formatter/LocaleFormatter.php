<?php

namespace Budgegeria\IntlFormat\Formatter;

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
                return Locale::getDisplayLanguage($value, $locale);
            },
            'region' => function($value) use ($locale) {
                return Locale::getDisplayRegion($value, $locale);
            },
        ];
    }

    /**
     * @inheritDoc
     */
    public function formatValue($typeSpecifier, $value)
    {
        return $this->formatFunctions[$typeSpecifier]($value);
    }

    /**
     * @inheritDoc
     */
    public function has($typeSpecifier)
    {
        return array_key_exists((string) $typeSpecifier, $this->formatFunctions);
    }
}