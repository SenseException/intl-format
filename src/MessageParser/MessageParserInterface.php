<?php

namespace Budgegeria\IntlFormat\MessageParser;


interface MessageParserInterface
{
    /**
     * @param string $message
     * @throws \Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException
     * @return MessageMetaData
     */
    public function parseMessage($message, array $values);
}