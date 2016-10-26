<?php
declare(strict_types=1);

namespace Budgegeria\IntlFormat\Formatter;

class SprintfFormatter implements FormatterInterface
{
    /**
     * @inheritdoc
     */
    public function formatValue(string $typeSpecifier, $value) : string
    {
        return sprintf('%' . $typeSpecifier, $value);
    }

    /**
     * @inheritdoc
     */
    public function has(string $typeSpecifier) : bool
    {
        return (bool) preg_match('/[\+\-]?(\'?.)?(?:\-\-)?\d*(?:\.?\d*)[%bcdeEfFgGosuxX]/', $typeSpecifier);
    }
}