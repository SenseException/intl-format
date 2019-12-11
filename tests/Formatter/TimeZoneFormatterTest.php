<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Tests\Formatter;

use Budgegeria\IntlFormat\Exception\InvalidValueException;
use Budgegeria\IntlFormat\Formatter\TimeZoneFormatter;
use DateTime;
use DateTimeImmutable;
use DateTimeInterface;
use DateTimeZone;
use function sprintf;
use IntlTimeZone;
use PHPUnit\Framework\TestCase;

class TimeZoneFormatterTest extends TestCase
{
    /**
     * @dataProvider provideTypeSpecifier
     *
     * @param string $typeSpecifier
     */
    public function testHas(string $typeSpecifier) : void
    {
        $formatter = new TimeZoneFormatter('en_US');

        self::assertTrue($formatter->has($typeSpecifier));
    }

    public function testHasIsFalse() : void
    {
        $messageFormatter = new TimeZoneFormatter('de_DE');

        self::assertFalse($messageFormatter->has('int'));
    }

    /**
     * @dataProvider provideTimeZones
     *
     * @param string $expected
     * @param string $typeSpecifier
     * @param mixed  $value
     */
    public function testFormatValue(string $expected, string $typeSpecifier, $value) : void
    {
        $formatter = new TimeZoneFormatter('en_US');

        self::assertSame($expected, $formatter->formatValue($typeSpecifier, $value));
    }

    /**
     * @dataProvider provideTypeSpecifier
     *
     * @param string $typeSpecifier
     */
    public function testFormatValueInvalidValue(string $typeSpecifier) : void
    {
        $this->expectException(InvalidValueException::class);
        $this->expectExceptionMessageMatches(sprintf(
            '/"%s, %s, %s"/',
            DateTimeInterface::class,
            DateTimeZone::class,
            IntlTimeZone::class
        ));
        $this->expectExceptionCode(10);

        $formatter = new TimeZoneFormatter('en_US');

        $formatter->formatValue($typeSpecifier, 'UTC');
    }

    /**
     * @return string[][]
     */
    public function provideTypeSpecifier() : array
    {
        return [
            'timeseries_id' => ['timeseries_id'],
            'timeseries_name' => ['timeseries_name'],
            'timeseries_short' => ['timeseries_short'],
        ];
    }

    /**
     * @return mixed[][]
     */
    public function provideTimeZones() : array
    {
        $datetime = new DateTime('2016-08-01', new DateTimeZone('US/Arizona'));
        $datetimeImmutable = new DateTimeImmutable('2016-05-01', new DateTimeZone('US/Arizona'));
        $timezone = $datetime->getTimezone();
        $intlTimezone = IntlTimeZone::fromDateTimeZone($timezone);

        return [
            'timeseries_id_ts' => ['US/Arizona', 'timeseries_id', $timezone],
            'timeseries_id_dt' => ['US/Arizona', 'timeseries_id', $datetime],
            'timeseries_id_dti' => ['US/Arizona', 'timeseries_id', $datetimeImmutable],
            'timeseries_id_its' => ['US/Arizona', 'timeseries_id', $intlTimezone],

            'timeseries_name_ts' => ['Mountain Standard Time', 'timeseries_name', $timezone],
            'timeseries_name_dt' => ['Mountain Standard Time', 'timeseries_name', $datetime],
            'timeseries_name_dti' => ['Mountain Standard Time', 'timeseries_name', $datetimeImmutable],
            'timeseries_name_its' => ['Mountain Standard Time', 'timeseries_name', $intlTimezone],

            'timeseries_short_ts' => ['MST', 'timeseries_short', $timezone],
            'timeseries_short_dt' => ['MST', 'timeseries_short', $datetime],
            'timeseries_short_dti' => ['MST', 'timeseries_short', $datetimeImmutable],
            'timeseries_short_its' => ['MST', 'timeseries_short', $intlTimezone],

            'timeseries_name_dst_start' => ['Central European Summer Time', 'timeseries_name', new DateTime('2016-03-27 02:00:00', new DateTimeZone('Europe/Berlin'))],
            'timeseries_name_dst_end' => ['Central European Summer Time', 'timeseries_name', new DateTime('2016-10-30 01:59:59', new DateTimeZone('Europe/Berlin'))],
            'timeseries_name_after_dst' => ['Central European Standard Time', 'timeseries_name', new DateTime('2016-10-30 02:59:59', new DateTimeZone('Europe/Berlin'))],
        ];
    }
}
