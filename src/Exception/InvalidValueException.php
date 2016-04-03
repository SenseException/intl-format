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
}