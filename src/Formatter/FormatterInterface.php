<?php

declare(strict_types=1);

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
     * @param mixed $value
     *
     * @throws InvalidValueException
     */
    public function formatValue(string $typeSpecifier, $value): string;

    /**
     * Check if Format has the given type specifier.
     *
     * Checks if this Formatter class has the given type specifier to
     * handle the formatting. If it returns true, the Formatter class
     * is able to handle the formatting using FormatterInterface::formatValue().
     *
     * @param string $typeSpecifier The type specifier without the prefix char.
     */
    public function has(string $typeSpecifier): bool;
}
