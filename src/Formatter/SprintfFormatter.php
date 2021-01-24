<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Formatter;

use function preg_match;
use function sprintf;

class SprintfFormatter implements FormatterInterface
{
    /**
     * @inheritdoc
     */
    public function formatValue(string $typeSpecifier, $value): string
    {
        return sprintf('%' . $typeSpecifier, $value);
    }

    public function has(string $typeSpecifier): bool
    {
        return (bool) preg_match('/[\+\-]?(\'?.)?(?:\-\-)?\d*(?:\.?\d*)[%bcdeEfFgGosuxX]/', $typeSpecifier);
    }
}
