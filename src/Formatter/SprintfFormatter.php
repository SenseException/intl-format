<?php

namespace Budgegeria\IntlFormat\Formatter;

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
        return (bool) preg_match('/[\+\-]?(\'?.)?(?:\-\-)?\d*(?:\.?\d*)[%bcdeEfFgGosuxX]/', $typeSpecifier);
    }
}