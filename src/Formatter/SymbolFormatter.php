<?php
declare(strict_types = 1);

namespace Budgegeria\IntlFormat\Formatter;

use NumberFormatter;

class SymbolFormatter implements FormatterInterface
{
    /**
     * @var int[]
     */
    private static $mapping = [
        'currency_symbol' => NumberFormatter::CURRENCY_SYMBOL,
        'currency_code' => NumberFormatter::INTL_CURRENCY_SYMBOL,
        'percent_symbol' => NumberFormatter::PERCENT_SYMBOL,
        'permill_symbol' => NumberFormatter::PERMILL_SYMBOL,
    ];

    /**
     * @inheritdoc
     */
    public function formatValue(string $typeSpecifier, $value) : string
    {
        return (new NumberFormatter($value, NumberFormatter::IGNORE))->getSymbol(self::$mapping[$typeSpecifier]);
    }

    /**
     * @inheritdoc
     */
    public function has(string $typeSpecifier) : bool
    {
        return isset(self::$mapping[$typeSpecifier]);
    }
}