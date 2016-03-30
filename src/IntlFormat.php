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
        $parsedMessage = preg_split('/(%[%]*(?:[\d]+\$)*[a-z0-9_]*)/i', $message, -1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_DELIM_CAPTURE);
        $typeSpecifiers = preg_grep('/(^%(?:[\d]+\$)*[a-z0-9_]+)/i', $parsedMessage);

        // Change escaped % to regular %
        $parsedMessage = preg_replace('/^%%/', '%', $parsedMessage);

        $values = $this->swapArguments($typeSpecifiers, $values);

        foreach ($typeSpecifiers as $key => $typeSpecifier) {
            $value = array_shift($values);
            $typeSpecifier = $this->normalize($typeSpecifier);
            if (null !== $formatter = $this->findFormatter($typeSpecifier)) {
                $parsedMessage[$key] = $formatter->formatValue($typeSpecifier, $value);
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

    private function normalize($typeSpecifier)
    {
        return preg_replace('/^%([0-9]\$)*/', '', $typeSpecifier);
    }

    /**
     * @param string[] $typeSpecifiers
     * @param array $values
     * @return array
     */
    private function swapArguments(array $typeSpecifiers, array $values)
    {
        $swappedValues = [];
        $typeSpecifiers = array_values($typeSpecifiers);

        foreach ($typeSpecifiers as $key => $typeSpecifier) {
            $matches = [];
            // TODO disallow 0
            if (1 === preg_match('/^%([0-9]+)\$/', $typeSpecifier, $matches)) {
                $index = $matches[1] - 1;
            } else {
                $index = $key;
            }

            if (!array_key_exists($index, $values)) {
                throw new LogicException(sprintf('The type specifier "%s" doesn\'t match with the given values.', $typeSpecifier));
            }

            $swappedValues[] = $values[$index];
        }

        if (0 === count($swappedValues)) {
            throw new LogicException('No type specifier are in the message text.');
        }

        if (count($typeSpecifiers) !== count($swappedValues)) {
            throw new LogicException(sprintf(
                'Value count of "%d" doesn\'t match type specifier count of "%d"',
                count($swappedValues),
                count($typeSpecifiers)
            ));
        }

        return $swappedValues;
    }
}