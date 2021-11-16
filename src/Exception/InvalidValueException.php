<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Exception;

use function gettype;
use function implode;
use function sprintf;

class InvalidValueException extends IntlFormatException
{
    /**
     * @param string[] $allowedTypes
     */
    public static function invalidValueType(mixed $value, array $allowedTypes): self
    {
        return new self(sprintf(
            'Invalid type "%s" of value. Allowed types: "%s".',
            gettype($value),
            implode(', ', $allowedTypes)
        ), 10);
    }

    public static function invalidLocale(string $locale): self
    {
        return new self(sprintf('"%s" is not a valid locale.', $locale), 20);
    }

    public static function invalidReturnType(mixed $value): self
    {
        return new self(sprintf('Unexpected return type "%s"', gettype($value)), 30);
    }
}
