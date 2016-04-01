<?php

namespace Budgegeria\IntlFormat\Exception;

class InvalidTypeSpecifierException extends IntlFormatException
{
    /**
     * @param $typeSpecifier
     * @return InvalidTypeSpecifierException
     */
    public static function unmatchedTypeSpecifier($typeSpecifier)
    {
        return new self(sprintf('The type specifier "%s" doesn\'t match with the given values.', (string) $typeSpecifier));
    }

    /**
     * @return InvalidTypeSpecifierException
     */
    public static function noTypeSpecifier()
    {
        return new self('No type specifier are in the message text.');
    }

    /**
     * @return InvalidTypeSpecifierException
     */
    public static function invalidTypeSpecifier($typeSpecifier)
    {
        return new self(sprintf('"%s" is not a valid type specifier.', (string) $typeSpecifier));
    }

    /**
     * @param int $valuesCount
     * @param int $typeSpecifiersCount
     * @return InvalidTypeSpecifierException
     */
    public static function invalidTypeSpecifierCount($valuesCount, $typeSpecifiersCount)
    {
        return new self(sprintf(
            'Value count of "%d" doesn\'t match type specifier count of "%d"',
            (int) $valuesCount,
            (int) $typeSpecifiersCount
        ));
    }
}