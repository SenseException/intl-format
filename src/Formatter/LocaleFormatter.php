<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use Locale;

class LocaleFormatter implements FormatterInterface
{
    /**
     * @var \Closure[]
     */
    private $formatFunctions;

    /**
     * @param string $locale
     */
    public function __construct(string $locale)
    {
        $this->formatFunctions = [
            'language' => static function($value) use ($locale) : string {
                $language = Locale::getDisplayLanguage($value, $locale);

                if ($value === $language) {
                    throw InvalidValueException::invalidLocale($value);
                }

                return $language;
            },
            'region' => static function($value) use ($locale) : string {
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
        return isset($this->formatFunctions[$typeSpecifier]);
    }
}
