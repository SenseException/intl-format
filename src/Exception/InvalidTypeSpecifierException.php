<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Exception;

use function sprintf;

class InvalidTypeSpecifierException extends IntlFormatException
{
    private const CODE_UNMATCHED_TYPESPECIFIER     = 10;
    private const CODE_NO_TYPESPECIFIER            = 20;
    private const CODE_INVALID_TYPESPECIFIER       = 30;
    private const CODE_INVALID_TYPESPECIFIER_COUNT = 40;

    public static function unmatchedTypeSpecifier(string $typeSpecifier): self
    {
        return new self(sprintf(
            'The type specifier "%s" doesn\'t match with the given values.',
            $typeSpecifier,
        ), self::CODE_UNMATCHED_TYPESPECIFIER);
    }

    public static function noTypeSpecifier(): self
    {
        return new self('No type specifier are in the message text.', self::CODE_NO_TYPESPECIFIER);
    }

    public static function invalidTypeSpecifier(string $typeSpecifier): self
    {
        return new self(sprintf('"%s" is not a valid type specifier.', $typeSpecifier), self::CODE_INVALID_TYPESPECIFIER);
    }

    public static function invalidTypeSpecifierCount(int $valuesCount, int $typeSpecifiersCount): self
    {
        return new self(sprintf(
            'Value count of "%d" doesn\'t match type specifier count of "%d"',
            $valuesCount,
            $typeSpecifiersCount,
        ), self::CODE_INVALID_TYPESPECIFIER_COUNT);
    }
}
