<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat;

use Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException;
use Budgegeria\IntlFormat\Formatter\FormatterInterface;
use Budgegeria\IntlFormat\MessageParser\MessageParserInterface;

use function array_reverse;
use function array_shift;
use function count;
use function implode;

final class IntlFormat implements IntlFormatInterface
{
    /** @var FormatterInterface[] */
    private array $formatters = [];

    /** @param iterable<FormatterInterface> $formatters */
    public function __construct(iterable $formatters, private MessageParserInterface $messageParser)
    {
        foreach ($formatters as $formatter) {
            $this->addFormatter($formatter);
        }
    }

    public function format(string $message, mixed ...$values): string
    {
        $messageMetaData = $this->messageParser->parseMessage($message, $values);
        $typeSpecifiers  = $messageMetaData->typeSpecifiers;
        $values          = $messageMetaData->values;
        $parsedMessage   = $messageMetaData->parsedMessage;

        if (count($values) === 0) {
            throw InvalidTypeSpecifierException::noTypeSpecifier();
        }

        if (count($typeSpecifiers) !== count($values)) {
            throw InvalidTypeSpecifierException::invalidTypeSpecifierCount(count($values), count($typeSpecifiers));
        }

        foreach ($typeSpecifiers as $key => $typeSpecifier) {
            /** @var mixed $value */
            $value = array_shift($values);

            $formatter = $this->findFormatter($typeSpecifier);
            if ($formatter === null) {
                continue;
            }

            $parsedMessage[$key] = $formatter->formatValue($typeSpecifier, $value);
        }

        return implode('', $parsedMessage);
    }

    public function addFormatter(FormatterInterface $formatter): void
    {
        $this->formatters[] = $formatter;
    }

    private function findFormatter(string $typeSpecifier): FormatterInterface|null
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
