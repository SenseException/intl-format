<?php

namespace Budgegeria\IntlFormat\Tests\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use Budgegeria\IntlFormat\Formatter\MessageFormatter;
use DateTime;
use DateTimeImmutable;
use IntlCalendar;
use PHPUnit\Framework\TestCase;

class MessageFormatterTest extends TestCase
{
    /**
     * @param string $typeSpecifier
     * @dataProvider provideTypeSpecifier
     */
    public function testHas($typeSpecifier)
    {
        $messageFormatter = new MessageFormatter('de_DE', $this->getTypeSpecifier(), function () {});

        self::assertTrue($messageFormatter->has($typeSpecifier));
    }

    public function testHasIsFalse()
    {
        $messageFormatter = new MessageFormatter('de_DE', $this->getTypeSpecifier(), function () {});

        self::assertFalse($messageFormatter->has('int'));
    }

    public function testFormatValueNumber()
    {
        $messageFormatter = MessageFormatter::createNumberValueFormatter('de_DE');

        self::assertSame('1.000,1', $messageFormatter->formatValue('number', 1000.1));
        self::assertSame('1.000', $messageFormatter->formatValue('integer', 1000.1));
        self::assertSame('1.001', $messageFormatter->formatValue('integer', 1001));
        self::assertSame('1.001', $messageFormatter->formatValue('integer', '1001'));
        self::assertSame('100%', preg_replace('/[^0-9%]/', '', $messageFormatter->formatValue('percent', 1)));
        self::assertSame('1.000,10€', preg_replace('/[^0-9,\.€]/u', '', $messageFormatter->formatValue('currency', 1000.1)));
    }

    /**
     * @dataProvider provideDate
     */
    public function testFormatValueDate($expected, $typeSpecifier, $value)
    {
        $messageFormatter = MessageFormatter::createDateValueFormatter('de_DE');

        self::assertSame($expected, $messageFormatter->formatValue($typeSpecifier, $value));
    }

    /**
     * @dataProvider provideTime
     */
    public function testFormatValueTime($expected, $typeSpecifier, $value)
    {
        $messageFormatter = MessageFormatter::createDateValueFormatter('de_DE');

        self::assertSame($expected, $messageFormatter->formatValue($typeSpecifier, $value));
    }

    public function testFormatValueSpellout()
    {
        $messageFormatter = MessageFormatter::createNumberValueFormatter('de_DE');

        self::assertSame('ein­tausend', $messageFormatter->formatValue('spellout', 1000));
        self::assertSame('ein­tausend Komma eins', $messageFormatter->formatValue('spellout', 1000.1));
    }

    /**
     * @dataProvider provideOrdinal
     */
    public function testFormatValueOrdinal($expected, $number)
    {
        $messageFormatter = MessageFormatter::createNumberValueFormatter('en_US');

        self::assertSame($expected, $messageFormatter->formatValue('ordinal', $number));
    }

    public function testFormatValueDuration()
    {
        $messageFormatter = MessageFormatter::createNumberValueFormatter('en_US');

        self::assertSame('1:01', $messageFormatter->formatValue('duration', 61));
    }

    /**
     * @dataProvider provideInvalidNumberValues
     */
    public function testFormatValueNumberTypeCheck($value)
    {
        $messageFormatter = MessageFormatter::createNumberValueFormatter('en_US');

        $this->expectException(InvalidValueException::class);
        $this->expectExceptionCode(10);

        $messageFormatter->formatValue('integer', $value);
    }

    /**
     * @dataProvider provideInvalidDateValues
     */
    public function testFormatValueDateTypeCheck($value)
    {
        $messageFormatter = MessageFormatter::createDateValueFormatter('en_US');

        $this->expectException(InvalidValueException::class);
        $this->expectExceptionCode(10);

        $messageFormatter->formatValue('date', $value);
    }

    public function testFormatValueInvalidReturnType()
    {
        $messageFormatter = new MessageFormatter('de_DE', ['a' => 'a'], function () {});

        $this->expectException(InvalidValueException::class);
        $this->expectExceptionCode(30);

        $messageFormatter->formatValue('a', []);
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
            'date_day' => 'date_day',
            'date_month' => 'date_month',
            'date_year' => 'date_year',
            'date_month_name' => 'date_month_name',
            'date_weekday' => 'date_weekday',
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
            'date_day' => ['date_day'],
            'date_month' => ['date_month'],
            'date_year' => ['date_year'],
            'date_month_name' => ['date_month_name'],
            'date_weekday' => ['date_weekday'],
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
        $dateTime = new DateTime('2016-03-01');
        $dateTimeImmutable = new DateTimeImmutable('2016-03-01');
        $calendar = IntlCalendar::fromDateTime($dateTime);

        return [
            'date' => ['01.03.2016', 'date', $dateTime],
            'date_immutable' => ['01.03.2016', 'date', $dateTimeImmutable],
            'date_calendar' => ['01.03.2016', 'date', $calendar],
            'date_timestamp' => ['01.03.2016', 'date', $dateTime->getTimestamp()],
            'date_short' => ['01.03.16', 'date_short', $dateTime],
            'date_short_immutable' => ['01.03.16', 'date_short', $dateTimeImmutable],
            'date_short_calendar' => ['01.03.16', 'date_short', $calendar],
            'date_short_timestamp' => ['01.03.16', 'date_short', $dateTime->getTimestamp()],
            'date_medium' => ['01.03.2016', 'date_medium', $dateTime],
            'date_medium_immutable' => ['01.03.2016', 'date_medium', $dateTimeImmutable],
            'date_medium_calendar' => ['01.03.2016', 'date_medium', $calendar],
            'date_medium_timestamp' => ['01.03.2016', 'date_medium', $dateTime->getTimestamp()],
            'date_long' => ['1. März 2016', 'date_long', $dateTime],
            'date_long_immutable' => ['1. März 2016', 'date_long', $dateTimeImmutable],
            'date_long_calendar' => ['1. März 2016', 'date_long', $calendar],
            'date_long_timestamp' => ['1. März 2016', 'date_long', $dateTime->getTimestamp()],
            'date_full' => ['Dienstag, 1. März 2016', 'date_full', $dateTime],
            'date_full_immutable' => ['Dienstag, 1. März 2016', 'date_full', $dateTimeImmutable],
            'date_full_calendar' => ['Dienstag, 1. März 2016', 'date_full', $calendar],
            'date_full_timestamp' => ['Dienstag, 1. März 2016', 'date_full', $dateTime->getTimestamp()],
            'date_year' => ['2016', 'date_year', $dateTime],
            'date_year_immutable' => ['2016', 'date_year', $dateTimeImmutable],
            'date_year_calendar' => ['2016', 'date_year', $calendar],
            'date_year_timestamp' => ['2016', 'date_year', $dateTime->getTimestamp()],
            'date_month' => ['3', 'date_month', $dateTime],
            'date_month_immutable' => ['3', 'date_month', $dateTimeImmutable],
            'date_month_calendar' => ['3', 'date_month', $calendar],
            'date_month_timestamp' => ['3', 'date_month', $dateTime->getTimestamp()],
            'date_month_name' => ['März', 'date_month_name', $dateTime],
            'date_month_name_immutable' => ['März', 'date_month_name', $dateTimeImmutable],
            'date_month_name_calendar' => ['März', 'date_month_name', $calendar],
            'date_month_name_timestamp' => ['März', 'date_month_name', $dateTime->getTimestamp()],
            'date_day' => ['1', 'date_day', $dateTime],
            'date_day_immutable' => ['1', 'date_day', $dateTimeImmutable],
            'date_day_calendar' => ['1', 'date_day', $calendar],
            'date_day_timestamp' => ['1', 'date_day', $dateTime->getTimestamp()],
            'date_weekday' => ['Dienstag', 'date_weekday', $dateTime],
            'date_weekday_immutable' => ['Dienstag', 'date_weekday', $dateTimeImmutable],
            'date_weekday_calendar' => ['Dienstag', 'date_weekday', $calendar],
            'date_weekday_timestamp' => ['Dienstag', 'date_weekday', $dateTime->getTimestamp()],
        ];
    }

    /**
     * @return array
     */
    public function provideTime()
    {
        $dateTime = new DateTime('2016-03-01 02:20:50', new \DateTimeZone('Europe/Berlin'));
        $dateTimeImmutable = new DateTimeImmutable('2016-03-01 02:20:50', new \DateTimeZone('Europe/Berlin'));
        $calendar = IntlCalendar::fromDateTime($dateTime);

        return [
            'time' => ['01:20:50', 'time', $dateTime],
            'time_immutable' => ['01:20:50', 'time', $dateTimeImmutable],
            'time_calendar' => ['01:20:50', 'time', $calendar],
            'time_timestamp' => ['01:20:50', 'time', $dateTime->getTimestamp()],
            'time_short' => ['01:20', 'time_short', $dateTime],
            'time_short_immutable' => ['01:20', 'time_short', $dateTimeImmutable],
            'time_short_calendar' => ['01:20', 'time_short', $calendar],
            'time_short_timestamp' => ['01:20', 'time_short', $dateTime->getTimestamp()],
            'time_medium_immutable' => ['01:20:50', 'time_medium', $dateTimeImmutable],
            'time_medium' => ['01:20:50', 'time_medium', $dateTime],
            'time_medium_calendar' => ['01:20:50', 'time_medium', $calendar],
            'time_medium_timestamp' => ['01:20:50', 'time_medium', $dateTime->getTimestamp()],
            'time_long_immutable' => ['01:20:50 GMT', 'time_long', $dateTimeImmutable],
            'time_long' => ['01:20:50 GMT', 'time_long', $dateTime],
            'time_long_calendar' => ['01:20:50 GMT', 'time_long', $calendar],
            'time_long_timestamp' => ['01:20:50 GMT', 'time_long', $dateTime->getTimestamp()],
            'time_full_immutable' => ['01:20:50 GMT', 'time_full', $dateTimeImmutable],
            'time_full' => ['01:20:50 GMT', 'time_full', $dateTime],
            'time_full_calendar' => ['01:20:50 GMT', 'time_full', $calendar],
            'time_full_timestamp' => ['01:20:50 GMT', 'time_full', $dateTime->getTimestamp()],
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
