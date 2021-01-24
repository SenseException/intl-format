<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\MessageParser;

use Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException;

interface MessageParserInterface
{
    /**
     * @param mixed[] $values
     *
     * @throws InvalidTypeSpecifierException
     */
    public function parseMessage(string $message, array $values): MessageMetaData;
}
