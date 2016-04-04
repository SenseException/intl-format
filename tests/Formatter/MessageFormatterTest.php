<?php

namespace Budgegeria\IntlFormat\Tests\Formatter;

use Budgegeria\IntlFormat\Formatter\MessageFormatter;
use DateTime;
use IntlCalendar;

class MessageFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $typeSpecifier
     * @dataProvider provideTypeSpecifier
     */
    public function testHas($typeSpecifier)
    {
        $messageFormatter = new MessageFormatter('de_DE', $this->getTypeSpecifier(), function () {});

        $this->assertTrue($messageFormatter->has($typeSpecifier));
    }

    public function testHasIsFalse()
    {
        $messageFormatter = new MessageFormatter('de_DE', $this->getTypeSpecifier(), function () {});

        $this->assertFalse($messageFormatter->has('int'));
    }

    public function testFormatValueNumber()
    {
        $messageFormatter = MessageFormatter::createNumberValueFormatter('de_DE');

        $this->assertSame('1.000,1', $messageFormatter->formatValue('number', 1000.1));
        $this->assertSame('1.000', $messageFormatter->formatValue('integer', 1000.1));
        $this->assertSame('1.001', $messageFormatter->formatValue('integer', 1001));
        $this->assertSame('1.001', $messageFormatter->formatValue('integer', '1001'));
        $this->assertSame('100%', preg_replace('/[^0-9%]/', '', $messageFormatter->formatValue('percent', 1)));
        $this->assertSame('1.000,10€', preg_replace('/[^0-9,\.€]/', '', $messageFormatter->formatValue('currency', 1000.1)));
    }

    /**
     * @dataProvider provideDate
     */
    public function testFormatValueDate($expected, $typeSpecifier, $value)
    {
        $messageFormatter = MessageFormatter::createDateValueFormatter('de_DE');

        $this->assertSame($expected, $messageFormatter->formatValue($typeSpecifier, $value));
    }

    /**
     * @dataProvider provideTime
     */
    public function testFormatValueTime($expected, $typeSpecifier, $value)
    {
        $messageFormatter = $messageFormatter = MessageFormatter::createDateValueFormatter('de_DE');

        $this->assertSame($expected, $messageFormatter->formatValue($typeSpecifier, $value));
    }

    public function testFormatValueSpellout()
    {
        $messageFormatter = MessageFormatter::createNumberValueFormatter('de_DE');

        $this->assertSame('ein­tausend', $messageFormatter->formatValue('spellout', 1000));
        $this->assertSame('ein­tausend Komma eins', $messageFormatter->formatValue('spellout', 1000.1));
    }

    /**
     * @dataProvider provideOrdinal
     */
    public function testFormatValueOrdinal($expected, $number)
    {
        $messageFormatter = MessageFormatter::createNumberValueFormatter('en_US');

        $this->assertSame($expected, $messageFormatter->formatValue('ordinal', $number));
    }

    public function testFormatValueDuration()
    {
        $messageFormatter = MessageFormatter::createNumberValueFormatter('en_US');

        $this->assertSame('1:01', $messageFormatter->formatValue('duration', 61));
    }

    /**
     * @expectedException \Budgegeria\IntlFormat\Exception\InvalidValueException
     * @dataProvider provideInvalidNumberValues
     */
    public function testFormatValueNumberTypeCheck($value)
    {
        $messageFormatter = MessageFormatter::createNumberValueFormatter('en_US');

        $messageFormatter->formatValue('integer', $value);
    }

    /**
     * @expectedException \Budgegeria\IntlFormat\Exception\InvalidValueException
     * @dataProvider provideInvalidDateValues
     */
    public function testFormatValueDateTypeCheck($value)
    {
        $messageFormatter = MessageFormatter::createDateValueFormatter('en_US');

        $messageFormatter->formatValue('date', $value);
    }

    /**
     * @return array
     */
    private function getTypeSpecifier()
    {
        return [
            'number' => 'number',
            'integer' => 'integer',
            'currency' => 'currency',
            'percent' => 'percent',
            'date' => 'date',
            'date_short' => 'date_short',
            'date_medium' => 'date_medium',
            'date_long' => 'date_long',
            'date_full' => 'date_full',
            'time' => 'time',
            'time_short' => 'time_short',
            'time_medium' => 'time_medium',
            'time_long' => 'time_long',
            'time_full' => 'time_full',
            'spellout' => 'spellout',
            'ordinal' => 'ordinal',
            'duration' => 'duration',
        ];
    }

    /**
     * @return array
     */
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

    /**
     * @return array
     */
    public function provideDate()
    {
        $date = new \DateTime('2016-03-01');
        $calendar = IntlCalendar::fromDateTime($date);

        return [
            'date' => ['01.03.2016', 'date', $date],
            'date_calendar' => ['01.03.2016', 'date', $calendar],
            'date_timestamp' => ['01.03.2016', 'date', $date->getTimestamp()],
            'date_short' => ['01.03.16', 'date_short', $date],
            'date_short_calendar' => ['01.03.16', 'date_short', $calendar],
            'date_short_timestamp' => ['01.03.16', 'date_short', $date->getTimestamp()],
            'date_medium' => ['01.03.2016', 'date_medium', $date],
            'date_medium_calendar' => ['01.03.2016', 'date_medium', $calendar],
            'date_medium_timestamp' => ['01.03.2016', 'date_medium', $date->getTimestamp()],
            'date_long' => ['1. März 2016', 'date_long', $date],
            'date_long_calendar' => ['1. März 2016', 'date_long', $calendar],
            'date_long_timestamp' => ['1. März 2016', 'date_long', $date->getTimestamp()],
            'date_full' => ['Dienstag, 1. März 2016', 'date_full', $date],
            'date_full_calendar' => ['Dienstag, 1. März 2016', 'date_full', $calendar],
            'date_full_timestamp' => ['Dienstag, 1. März 2016', 'date_full', $date->getTimestamp()],
        ];
    }

    /**
     * @return array
     */
    public function provideTime()
    {
        $date = new DateTime('2016-03-01 02:20:50', new \DateTimeZone('Europe/Berlin'));
        $calendar = IntlCalendar::fromDateTime($date);

        return [
            'time' => ['01:20:50', 'time', $date],
            'time_calendar' => ['01:20:50', 'time', $calendar],
            'time_timestamp' => ['01:20:50', 'time', $date->getTimestamp()],
            'time_short' => ['01:20', 'time_short', $date],
            'time_short_calendar' => ['01:20', 'time_short', $calendar],
            'time_short_timestamp' => ['01:20', 'time_short', $date->getTimestamp()],
            'time_medium' => ['01:20:50', 'time_medium', $date],
            'time_medium_calendar' => ['01:20:50', 'time_medium', $calendar],
            'time_medium_timestamp' => ['01:20:50', 'time_medium', $date->getTimestamp()],
            'time_long' => ['01:20:50 GMT', 'time_long', $date],
            'time_long_calendar' => ['01:20:50 GMT', 'time_long', $calendar],
            'time_long_timestamp' => ['01:20:50 GMT', 'time_long', $date->getTimestamp()],
            'time_full' => ['01:20:50 GMT', 'time_full', $date],
            'time_full_calendar' => ['01:20:50 GMT', 'time_full', $calendar],
            'time_full_timestamp' => ['01:20:50 GMT', 'time_full', $date->getTimestamp()],
        ];
    }

    /**
     * @return array
     */
    public function provideOrdinal()
    {
        return [
            'first' => ['1st', 1],
            'second' => ['2nd', 2],
            'third' => ['3rd', 3],
            'fourth' => ['4th', 4],
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidNumberValues()
    {
        return [
            'string' => ['foo'],
            'object' => [new \ArrayObject()],
            'bool' => [true],
            'array' => [[1,2,3]],
            'null' => [null],
            'closure' => [function () {}],
        ];
    }

    /**
     * @return array
     */
    public function provideInvalidDateValues()
    {
        $invalidDateValues = [
            'float' => [100.1],
        ];

        return array_merge($invalidDateValues, $this->provideInvalidNumberValues());
    }
}
