<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\MessageParser;

interface MessageParserInterface
{
    /**
     * @param string $message
     * @param mixed[] $values
     * @throws \Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException
     * @return MessageMetaData
     */
    public function parseMessage(string $message, array $values): MessageMetaData;
}