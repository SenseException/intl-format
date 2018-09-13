<?php
declare(strict_types = 1);

namespace Budgegeria\IntlFormat;

use Budgegeria\IntlFormat\Formatter\FormatterInterface;
use Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException;
use Budgegeria\IntlFormat\MessageParser\MessageParserInterface;
use Budgegeria\IntlFormat\MessageParser\SprintfParser;

class IntlFormat
{
    /**
     * @var FormatterInterface[]
     */
    private $formatters = [];

    /**
     * @var MessageParserInterface
     */
    private $messageParser;

    /**
     * @param FormatterInterface[] $formatters
     * @param MessageParserInterface $messageParser
     */
    public function __construct(array $formatters, MessageParserInterface $messageParser = null)
    {
        $this->messageParser = $messageParser ?? new SprintfParser();
        foreach ($formatters as $formatter) {
            $this->addFormatter($formatter);
        }
    }

    /**
     * Formats the given message.
     *
     * Formats the message by the given formatters.
     *
     * @param string $message Message string containing type specifier for the values
     * @param mixed ...$values multiple values used for the message's type specifier
     * @throws InvalidTypeSpecifierException
     * @return string
     */
    public function format(string $message, ...$values) : string
    {
        $messageMetaData = $this->messageParser->parseMessage($message, $values);
        $typeSpecifiers = $messageMetaData->typeSpecifiers;
        $values = $messageMetaData->values;
        $parsedMessage = $messageMetaData->parsedMessage;

        if (0 === count($values)) {
            throw InvalidTypeSpecifierException::noTypeSpecifier();
        }

        if (count($typeSpecifiers) !== count($values)) {
            throw InvalidTypeSpecifierException::invalidTypeSpecifierCount(count($values), count($typeSpecifiers));
        }

        foreach ($typeSpecifiers as $key => $typeSpecifier) {
            $value = array_shift($values);

            $formatter = $this->findFormatter($typeSpecifier);
            if (null !== $formatter) {
                $parsedMessage[$key] = $formatter->formatValue($typeSpecifier, $value);
            }
        }

        return implode('', $parsedMessage);
    }

    /**
     * @param FormatterInterface $formatter
     */
    public function addFormatter(FormatterInterface $formatter) : void
    {
        $this->formatters[] = $formatter;
    }

    /**
     * @param string $typeSpecifier
     * @return FormatterInterface|null
     */
    private function findFormatter(string $typeSpecifier) : ?FormatterInterface
    {
        $formatters = array_reverse($this->formatters);
        foreach ($formatters as $formatter) {
            if ($formatter->has($typeSpecifier)) {
                return $formatter;
            }
        }

        return null;
    }
}