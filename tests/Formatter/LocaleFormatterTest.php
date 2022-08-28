<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Tests\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use Budgegeria\IntlFormat\Formatter\LocaleFormatter;
use PHPUnit\Framework\TestCase;

class LocaleFormatterTest extends TestCase
{
    public function testHas(): void
    {
        $localeFormatter = new LocaleFormatter('de_DE');

        self::assertTrue($localeFormatter->has('language'));
        self::assertTrue($localeFormatter->has('region'));
    }

    public function testHasIsFalse(): void
    {
        $localeFormatter = new LocaleFormatter('de_DE');

        self::assertFalse($localeFormatter->has('foo'));
    }

    /** @dataProvider provideLanguages */
    public function testFormatValueLanguage(string $expected, string $locale): void
    {
        $localeFormatter = new LocaleFormatter('de_DE');

        self::assertSame($expected, $localeFormatter->formatValue('language', $locale));
    }

    /** @dataProvider provideRegions */
    public function testFormatValueRegion(string $expected, string $locale): void
    {
        $localeFormatter = new LocaleFormatter('de_DE');

        self::assertSame($expected, $localeFormatter->formatValue('region', $locale));
    }

    /** @dataProvider provideTypeSpecifier */
    public function testFormatValueException(string $typeSpecifier): void
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionCode(20);
        $localeFormatter = new LocaleFormatter('de_DE');

        $localeFormatter->formatValue($typeSpecifier, 'foobarbaz');
    }

    /** @return string[][] */
    public function provideLanguages(): array
    {
        return [
            ['Italienisch', 'it_IT'],
            ['Italienisch', 'it'],
            ['Deutsch', 'de_DE'],
            ['Deutsch', 'de'],
            ['Englisch', 'en_GB'],
            ['Englisch', 'en'],
            ['Deutsch', 'de_CH'],
            ['Italienisch', 'it_CH'],
        ];
    }

    /** @return string[][] */
    public function provideRegions(): array
    {
        return [
            ['Italien', 'it_IT'],
            ['Deutschland', 'de_DE'],
            ['Vereinigtes Königreich', 'en_GB'],
            ['Schweiz', 'de_CH'],
            ['Schweiz', 'it_CH'],
        ];
    }

    /** @return string[][] */
    public function provideTypeSpecifier(): array
    {
        return [
            ['region'],
            ['language'],
        ];
    }
}
