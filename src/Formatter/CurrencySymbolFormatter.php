<?php
declare(strict_types = 1);

namespace Budgegeria\IntlFormat\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use NumberFormatter;

class CurrencySymbolFormatter implements FormatterInterface
{
    /**
     * @var string
     */
    private $locale;

    /**
     * @param string $locale
     */
    public function __construct(string $locale)
    {
        $this->locale = $locale;
    }

    /**
     * @inheritdoc
     */
    public function formatValue(string $typeSpecifier, $value) : string
    {
        if (!is_string($value)) {
            throw InvalidValueException::invalidValueType($value, ['string']);
        }

        if ('' !== $value) {
            $value = '@currency='.$value;
        }

        return (new NumberFormatter($this->locale.$value, NumberFormatter::IGNORE))
            ->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
    }

    /**
     * @inheritdoc
     */
    public function has(string $typeSpecifier) : bool
    {
        return 'currency_symbol' === $typeSpecifier;
    }
}