<?php

namespace Budgegeria\IntlFormat\Formatter;

class SprintfFormatter implements FormatterInterface
{
    /**
     * @inheritdoc
     */
    public function formatValue($typeSpecifier, $value) : string
    {
        return sprintf('%' . $typeSpecifier, $value);
    }

    /**
     * @inheritdoc
     */
    public function has($typeSpecifier) : bool
    {
        return (bool) preg_match('/[\+\-]?(\'?.)?(?:\-\-)?\d*(?:\.?\d*)[%bcdeEfFgGosuxX]/', $typeSpecifier);
    }
}