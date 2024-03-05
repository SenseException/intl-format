<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use NumberFormatter;
use Override;

use function array_filter;
use function explode;
use function implode;
use function is_string;
use function str_contains;

class CurrencySymbolFormatter implements FormatterInterface
{
    /** @var string[] */
    private array $keywords = [];

    public function __construct(private string $locale)
    {
        if (! str_contains($locale, '@')) {
            return;
        }

        $localeParts = explode('@', $locale);
        $keywords    = explode(';', $localeParts[1]);

        $this->locale   = $localeParts[0];
        $this->keywords = array_filter($keywords, static fn (string $value) => ! str_contains($value, 'currency='));
    }

    #[Override]
    public function formatValue(string $typeSpecifier, mixed $value): string
    {
        if (! is_string($value)) {
            throw InvalidValueException::invalidValueType($value, ['string']);
        }

        return (new NumberFormatter($this->locale . $this->getKeywords($value), NumberFormatter::IGNORE))
            ->getSymbol(NumberFormatter::CURRENCY_SYMBOL);
    }

    #[Override]
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
