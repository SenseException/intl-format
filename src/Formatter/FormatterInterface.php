<?php

namespace Budgegeria\IntlFormat\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;

interface FormatterInterface
{
    /**
     * Formats the given value.
     *
     * Formats the given value by a formatting strategy that is
     * mapped by the type specifier.
     *
     * @param string $typeSpecifier
     * @param mixed $value
     * @return string
     * @throws InvalidValueException
     */
    public function formatValue($typeSpecifier, $value);

    /**
     * Check if Format has the given type specifier.
     *
     * Checks if this Formatter class has the given type specifier to
     * handle the formatting. If it returns true, the Formatter class
     * is able to handle the formatting using FormatterInterface::formatValue().
     *
     * @param string $typeSpecifier The type specifier without the prefix char.
     * @return bool
     */
    public function has($typeSpecifier);
}