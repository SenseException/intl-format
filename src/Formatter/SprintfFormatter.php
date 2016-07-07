<?php

namespace Budgegeria\IntlFormat\Formatter;

use IntlCalendar;
use IntlTimeZone;
use DateTimeInterface;
use DateTimeZone;
use Budgegeria\IntlFormat\Exception\InvalidValueException;

class SprintfFormatter implements FormatterInterface
{
    /**
     * @inheritdoc
     */
    public function formatValue($typeSpecifier, $value)
    {
        return sprintf('%' . $typeSpecifier, $value);
    }

    /**
     * @inheritdoc
     */
    public function has($typeSpecifier)
    {
        return (bool) preg_match('/[\+\-]?(\'?.)?\-?\d*(?:\.?\d*)[%bcdeEfFgGosuxX]/', $typeSpecifier);
    }
}