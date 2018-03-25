<?php
declare(strict_types = 1);

namespace Budgegeria\IntlFormat\MessageParser;

use Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException;

class SprintfParser implements MessageParserInterface
{
    /**
     * @param string $message
     * @param array $values
     * @throws \Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException
     * @return MessageMetaData
     */
    public function parseMessage(string $message, array $values) : MessageMetaData
    {
        $parsedMessage = preg_split('/(%[%]?(?:[0-9]+\$)?\.?[0-9]*[a-z0-9_]*)/i', $message, -1, PREG_SPLIT_NO_EMPTY | PREG_SPLIT_DELIM_CAPTURE);
        $typeSpecifiers = preg_grep('/(^%(?:[0-9]+\$)?\.?[0-9]*[a-z0-9_]+)/i', (array) $parsedMessage);

        // Change escaped % to regular %
        $parsedMessage = preg_replace('/^%%/', '%', $parsedMessage);

        $values = $this->swapArguments($typeSpecifiers, $values);

        // Remove % and value position from type specifiers after argument swapping
        $typeSpecifiers = preg_replace('/^%([0-9]\$)*/', '', $typeSpecifiers);

        return new MessageMetaData($parsedMessage, $typeSpecifiers, $values);
    }

    /**
     * @param string[] $typeSpecifiers
     * @param array $values
     * @return array
     * @throws InvalidTypeSpecifierException
     */
    private function swapArguments(array $typeSpecifiers, array $values) : array
    {
        $swappedValues = [];
        $typeSpecifiers = array_values($typeSpecifiers);

        foreach ($typeSpecifiers as $key => $typeSpecifier) {
            $matches = [];

            $index = $key;
            if (1 === preg_match('/^%([0-9]+)\$/', $typeSpecifier, $matches)) {
                if ('0' === $matches[1]) {
                    throw InvalidTypeSpecifierException::invalidTypeSpecifier($typeSpecifier);
                }

                $index = $matches[1] - 1;
            }

            if (!isset($values[$index])) {
                throw InvalidTypeSpecifierException::unmatchedTypeSpecifier($typeSpecifier);
            }

            $swappedValues[] = $values[$index];
        }

        return $swappedValues;
    }
}