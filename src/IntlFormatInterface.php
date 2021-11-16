<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat;

use Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException;

interface IntlFormatInterface extends FormatterStorageInterface
{
    /**
     * Formats the given message.
     *
     * Formats the message by the given formatters.
     *
     * @param string $message   Message string containing type specifier for the values
     * @param mixed  ...$values multiple values used for the message's type specifier
     *
     * @throws InvalidTypeSpecifierException
     */
    public function format(string $message, mixed ...$values): string;
}
