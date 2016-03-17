<?php

namespace Budgegeria\IntlFormat;

use Budgegeria\IntlFormat\Formatter\FormatterInterface;
use LogicException;

class IntlFormat
{
    /**
     * @var FormatterInterface[]
     */
    private $formatters = [];

    /**
     * @param FormatterInterface[] $formatters
     */
    public function __construct(array $formatters)
    {
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
     * @param mixed $values multiple values used for the message's type specifier
     * @return string
     */
    public function format($message, ...$values)
    {
        $parsedMessage = preg_split('/(%[%]*(?:[\d]+\$)*[a-z0-9_]+)/i', $message, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
        $typeSpecifiers = preg_grep('/(%(?:[\d]+\$)*[a-z0-9_]+)/i', $parsedMessage);

        if (count($typeSpecifiers) !== count($values)) {
            throw new LogicException(sprintf(
                'Value count of "%d" doesn\'t match type specifier count of "%d"',
                count($values),
                count($typeSpecifiers)
            ));
        }

        foreach ($typeSpecifiers as $key => $typeSpecifier) {
            $value = array_shift($values);
            if (null !== $formatter = $this->findFormatter(ltrim($typeSpecifier, '%'))) {
                $parsedMessage[$key] = $formatter->formatValue(ltrim($typeSpecifier, '%'), $value);
            }
        }
        $message = implode('', $parsedMessage);

        return $message;
    }

    /**
     * @param FormatterInterface $formatter
     */
    public function addFormatter(FormatterInterface $formatter)
    {
        $this->formatters[] = $formatter;
    }

    /**
     * @param string $typeSpecifier
     * @return FormatterInterface|null
     */
    private function findFormatter($typeSpecifier)
    {
        foreach ($this->formatters as $formatter) {
            if ($formatter->has($typeSpecifier)) {
                return $formatter;
            }
        }

        return null;
    }
}