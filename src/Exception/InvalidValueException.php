<?php

namespace Budgegeria\IntlFormat\Exception;

class InvalidValueException extends IntlFormatException
{
    /**
     * @param mixed $value
     * @param array $allowedTypes
     * @return InvalidValueException
     */
    public static function invalidValueType($value, array $allowedTypes)
    {
        return new self(sprintf(
            'Invalid type "%s" of value. Allowed types: "%s".',
            gettype($value),
            implode(', ', $allowedTypes)
        ));
    }

    /**
     * @param string $locale
     * @return InvalidValueException
     */
    public static function invalidLocale($locale)
    {
        if (is_object($locale)) {
            $locale = '[object]';
        }

        return new self(sprintf('"%s" is not a valid locale.', (string) $locale));
    }
}