<?php
declare(strict_types = 1);

namespace Budgegeria\IntlFormat\Tests\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use Budgegeria\IntlFormat\Formatter\LocaleFormatter;
use PHPUnit\Framework\TestCase;

class LocaleFormatterTest extends TestCase
{
    public function testHas()
    {
        $localeFormatter = new LocaleFormatter('de_DE');

        self::assertTrue($localeFormatter->has('language'));
        self::assertTrue($localeFormatter->has('region'));
    }

    public function testHasIsFalse()
    {
        $localeFormatter = new LocaleFormatter('de_DE');

        self::assertFalse($localeFormatter->has('foo'));
    }

    /**
     * @dataProvider provideLanguages
     */
    public function testFormatValueLanguage($expected, $locale)
    {
        $localeFormatter = new LocaleFormatter('de_DE');

        self::assertSame($expected, $localeFormatter->formatValue('language', $locale));
    }

    /**
     * @dataProvider provideRegions
     */
    public function testFormatValueRegion($expected, $locale)
    {
        $localeFormatter = new LocaleFormatter('de_DE');

        self::assertSame($expected, $localeFormatter->formatValue('region', $locale));
    }

    /**
     * @dataProvider provideTypeSpecifier
     */
    public function testFormatValueException($typeSpecifier)
    {
        $this->expectException(InvalidValueException::class);
        $localeFormatter = new LocaleFormatter('de_DE');

        $localeFormatter->formatValue($typeSpecifier, 'foobarbaz');
    }

    /**
     * @return array
     */
    public function provideLanguages()
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

    /**
     * @return array
     */
    public function provideRegions()
    {
        return [
            ['Italien', 'it_IT'],
            ['Deutschland', 'de_DE'],
            ['Vereinigtes Königreich', 'en_GB'],
            ['Schweiz', 'de_CH'],
            ['Schweiz', 'it_CH'],
        ];
    }

    /**
     * @return array
     */
    public function provideTypeSpecifier()
    {
        return [
            ['region'],
            ['language'],
        ];
    }
}
