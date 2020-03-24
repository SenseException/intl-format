<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Exception;

use function sprintf;

class InvalidTypeSpecifierException extends IntlFormatException
{
    /**
     * @param string $typeSpecifier
     * @return InvalidTypeSpecifierException
     */
    public static function unmatchedTypeSpecifier(string $typeSpecifier): self
    {
        return new self(sprintf('The type specifier "%s" doesn\'t match with the given values.', $typeSpecifier), 10);
    }

    /**
     * @return InvalidTypeSpecifierException
     */
    public static function noTypeSpecifier(): self
    {
        return new self('No type specifier are in the message text.', 20);
    }

    /**
     * @return InvalidTypeSpecifierException
     */
    public static function invalidTypeSpecifier(string $typeSpecifier): self
    {
        return new self(sprintf('"%s" is not a valid type specifier.', $typeSpecifier), 30);
    }

    /**
     * @param int $valuesCount
     * @param int $typeSpecifiersCount
     * @return InvalidTypeSpecifierException
     */
    public static function invalidTypeSpecifierCount(int $valuesCount, int $typeSpecifiersCount): self
    {
        return new self(sprintf(
            'Value count of "%d" doesn\'t match type specifier count of "%d"',
            $valuesCount,
            $typeSpecifiersCount
        ), 40);
    }
}