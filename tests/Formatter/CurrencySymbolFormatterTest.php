<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Tests\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use Budgegeria\IntlFormat\Formatter\CurrencySymbolFormatter;
use PHPUnit\Framework\TestCase;

class CurrencySymbolFormatterTest extends TestCase
{
    public function testHas(): void
    {
        $formatter = new CurrencySymbolFormatter('fr_FR');

        self::assertTrue($formatter->has('currency_symbol'));
    }

    public function testHasIsFalse(): void
    {
        $messageFormatter = new CurrencySymbolFormatter('fr_FR');

        self::assertFalse($messageFormatter->has('int'));
    }

    public function testFormatValueIso4217(): void
    {
        $formatter = new CurrencySymbolFormatter('fr_FR');

        self::assertSame('£GB', $formatter->formatValue('currency_symbol', 'GBP'));
    }

    public function testFormatValueLocaleSpecific(): void
    {
        $formatter = new CurrencySymbolFormatter('fr_FR');

        self::assertSame('€', $formatter->formatValue('currency_symbol', ''));
    }

    public function testFormatValueInvalidValueType(): void
    {
        $formatter = new CurrencySymbolFormatter('fr_FR');

        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessageMatches('/"string"/');
        $this->expectExceptionCode(10);

        $formatter->formatValue('currency_symbol', null);
    }

    /**
     * @dataProvider provideLocales
     */
    public function testFormatValueWithLocaleKeywords(string $locale): void
    {
        $formatter = new CurrencySymbolFormatter($locale);

        self::assertSame('£GB', $formatter->formatValue('currency_symbol', 'GBP'));
        self::assertSame('€', $formatter->formatValue('currency_symbol', ''));
    }

    /**
     * @return string[][]
     */
    public function provideLocales(): array
    {
        return [
            ['fr_FR@currency=USD;collation=phonebook'],
            ['fr_FR@collation=phonebook;currency=USD'],
            ['fr_FR@calendar=islamic-civil;currency=USD;collation=phonebook'],
            ['fr_FR@currency=USD'],
        ];
    }
}
