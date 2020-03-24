<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat;

use Budgegeria\IntlFormat\Formatter\FormatterInterface;
use Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException;
use Budgegeria\IntlFormat\MessageParser\MessageParserInterface;
use function array_reverse;
use function array_shift;
use function count;

final class IntlFormat implements IntlFormatInterface
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
    public function __construct(iterable $formatters, MessageParserInterface $messageParser)
    {
        $this->messageParser = $messageParser;
        foreach ($formatters as $formatter) {
            $this->addFormatter($formatter);
        }
    }

    /**
     * @inheritDoc
     */
    public function format(string $message, ...$values): string
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
     * @inheritDoc
     */
    public function addFormatter(FormatterInterface $formatter): void
    {
        $this->formatters[] = $formatter;
    }

    /**
     * @param string $typeSpecifier
     * @return FormatterInterface|null
     */
    private function findFormatter(string $typeSpecifier): ?FormatterInterface
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