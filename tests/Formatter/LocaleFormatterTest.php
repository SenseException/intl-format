<?php

namespace Budgegeria\IntlFormat\Tests\Formatter;

use Budgegeria\IntlFormat\Formatter\LocaleFormatter;

class LocaleFormatterTest extends \PHPUnit_Framework_TestCase
{
    public function testHas()
    {
        $localeFormatter = new LocaleFormatter('de_DE');

        $this->assertTrue($localeFormatter->has('language'));
        $this->assertTrue($localeFormatter->has('region'));
    }

    public function testHasIsFalse()
    {
        $localeFormatter = new LocaleFormatter('de_DE');

        $this->assertFalse($localeFormatter->has('foo'));
    }

    /**
     * @dataProvider provideLanguages
     */
    public function testFormatValueLanguage($expected, $locale)
    {
        $localeFormatter = new LocaleFormatter('de_DE');

        $this->assertSame($expected, $localeFormatter->formatValue('language', $locale));
    }

    /**
     * @dataProvider provideRegions
     */
    public function testFormatValueRegion($expected, $locale)
    {
        $localeFormatter = new LocaleFormatter('de_DE');

        $this->assertSame($expected, $localeFormatter->formatValue('region', $locale));
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
}
