<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use NumberFormatter;

use function array_filter;
use function explode;
use function implode;
use function is_int;
use function is_string;
use function strpos;

class CurrencySymbolFormatter implements FormatterInterface
{
    /** @var string */
    private $locale;

    /** @var string[] */
    private $keywords = [];

    public function __construct(string $locale)
    {
        if (strpos($locale, '@') !== false) {
            $localeParts = explode('@', $locale);
            $keywords    = explode(';', $localeParts[1]);

            $this->locale   = $localeParts[0];
            $this->keywords = array_filter($keywords, static function (string $value) {
                return ! is_int(strpos($value, 'currency='));
            });

            return;
        }

        $this->locale = $locale;
    }

    /**
     * @inheritdoc
     */
    public function formatValue(string $typeSpecifier, $value): string
    {
        if (! is_string($value)) {
            throw InvalidValueException::invalidValueType($value, ['string']);
        }

        return (new NumberFormatter($this->locale . $this->getKeywords($value), NumberFormatter::IGNORE))
            ->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
    }

    public function has(string $typeSpecifier): bool
    {
        return $typeSpecifier === 'currency_symbol';
    }

    private function getKeywords(string $iso4217): string
    {
        $keywords = $this->keywords;
        if ($iso4217 !== '') {
            $keywords[] = 'currency=' . $iso4217;
        }

        return '@' . implode(';', $keywords);
    }
}
