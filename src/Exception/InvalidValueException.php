<?php
declare(strict_types = 1);

namespace Budgegeria\IntlFormat\Exception;

class InvalidValueException extends IntlFormatException
{
    /**
     * @param mixed $value
     * @param array $allowedTypes
     * @return InvalidValueException
     */
    public static function invalidValueType($value, array $allowedTypes) : self
    {
        return new self(sprintf(
            'Invalid type "%s" of value. Allowed types: "%s".',
            gettype($value),
            implode(', ', $allowedTypes)
        ), 10);
    }

    /**
     * @param string $locale
     * @return InvalidValueException
     */
    public static function invalidLocale(string $locale) : self
    {
        return new self(sprintf('"%s" is not a valid locale.', $locale), 20);
    }
}