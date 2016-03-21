<?php

namespace Budgegeria\IntlFormat\Tests\Formatter;

use Budgegeria\IntlFormat\Formatter\MessageFormatter;

class MessageFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $typeSpecifier
     * @dataProvider provideTypeSpecifier
     */
    public function testHas($typeSpecifier)
    {
        $messageFormatter = new MessageFormatter('de');

        $this->assertTrue($messageFormatter->has($typeSpecifier));
    }

    public function testHasIsFalse()
    {
        $messageFormatter = new MessageFormatter('de_DE');

        $this->assertFalse($messageFormatter->has('int'));
    }

    public function testFormatValueNumber()
    {
        $messageFormatter = new MessageFormatter('de_DE');

        $this->assertSame('1.000,1', $messageFormatter->formatValue('number', 1000.1));
        $this->assertSame('1.000', $messageFormatter->formatValue('integer', 1000.1));
        $this->assertSame('100%', preg_replace('/[^0-9%]/', '', $messageFormatter->formatValue('percent', 1)));
        $this->assertSame('1.000,10€', preg_replace('/[^0-9,\.€]/', '', $messageFormatter->formatValue('currency', 1000.1)));
    }

    /**
     * @dataProvider provideDate
     */
    public function testFormatValueDate($expected, $typeSpecifier, $value)
    {
        $messageFormatter = new MessageFormatter('de_DE');

        $this->assertSame($expected, $messageFormatter->formatValue($typeSpecifier, $value));
    }

    /**
     * @dataProvider provideTime
     */
    public function testFormatValueTime($expected, $typeSpecifier, $value)
    {
        $messageFormatter = new MessageFormatter('de_DE');

        $this->assertSame($expected, $messageFormatter->formatValue($typeSpecifier, $value));
    }

    public function testFormatValueSpellout()
    {
        $messageFormatter = new MessageFormatter('de_DE');

        $this->assertSame('ein­tausend', $messageFormatter->formatValue('spellout', 1000));
    }

    public function provideTypeSpecifier()
    {
        return [
            'number' => ['number'],
            'integer' => ['integer'],
            'currency' => ['currency'],
            'percent' => ['percent'],
            'date' => ['date'],
            'date_short' => ['date_short'],
            'date_medium' => ['date_medium'],
            'date_long' => ['date_long'],
            'date_full' => ['date_full'],
            'time' => ['time'],
            'time_short' => ['time_short'],
            'time_medium' => ['time_medium'],
            'time_long' => ['time_long'],
            'time_full' => ['time_full'],
            'spellout' => ['spellout'],
            'ordinal' => ['ordinal'],
            'duration' => ['duration'],
        ];
    }

    public function provideDate()
    {
        $date = new \DateTime('2016-03-01');

        return [
            'date' => ['01.03.2016', 'date', $date],
            'date_timestamp' => ['01.03.2016', 'date', $date->getTimestamp()],
            'date_short' => ['01.03.16', 'date_short', $date],
            'date_short_timestamp' => ['01.03.16', 'date_short', $date->getTimestamp()],
            'date_medium' => ['01.03.2016', 'date_medium', $date],
            'date_medium_timestamp' => ['01.03.2016', 'date_medium', $date->getTimestamp()],
            'date_long' => ['1. März 2016', 'date_long', $date],
            'date_long_timestamp' => ['1. März 2016', 'date_long', $date->getTimestamp()],
            'date_full' => ['Dienstag, 1. März 2016', 'date_full', $date],
            'date_full_timestamp' => ['Dienstag, 1. März 2016', 'date_full', $date->getTimestamp()],
        ];
    }

    public function provideTime()
    {
        $date = new \DateTime('2016-03-01 02:20:50', new \DateTimeZone('Europe/Berlin'));

        return [
            'time' => ['01:20:50', 'time', $date],
            'time_timestamp' => ['01:20:50', 'time', $date->getTimestamp()],
            'time_short' => ['01:20', 'time_short', $date],
            'time_short_timestamp' => ['01:20', 'time_short', $date->getTimestamp()],
            'time_medium' => ['01:20:50', 'time_medium', $date],
            'time_medium_timestamp' => ['01:20:50', 'time_medium', $date->getTimestamp()],
            'time_long' => ['01:20:50 GMT', 'time_long', $date], // MessageFormat behaviour
            'time_long_timestamp' => ['01:20:50 GMT', 'time_long', $date->getTimestamp()], // MessageFormat behaviour
            'time_full' => ['01:20:50 GMT', 'time_full', $date], // MessageFormat behaviour
            'time_full_timestamp' => ['01:20:50 GMT', 'time_full', $date->getTimestamp()], // MessageFormat behaviour
        ];
    }
}
