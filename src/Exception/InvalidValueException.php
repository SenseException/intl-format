<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Exception;

use function gettype;
use function implode;
use function sprintf;

class InvalidValueException extends IntlFormatException
{
    private const CODE_INVALID_VALUETYPE  = 10;
    private const CODE_INVALID_LOCALE     = 20;
    private const CODE_INVALID_RETURNTYPE = 30;

    /** @param string[] $allowedTypes */
    public static function invalidValueType(mixed $value, array $allowedTypes): self
    {
        return new self(sprintf(
            'Invalid type "%s" of value. Allowed types: "%s".',
            gettype($value),
            implode(', ', $allowedTypes),
        ), self::CODE_INVALID_VALUETYPE);
    }

    public static function invalidLocale(string $locale): self
    {
        return new self(sprintf('"%s" is not a valid locale.', $locale), self::CODE_INVALID_LOCALE);
    }

    public static function invalidReturnType(mixed $value): self
    {
        return new self(sprintf('Unexpected return type "%s"', gettype($value)), self::CODE_INVALID_RETURNTYPE);
    }
}
