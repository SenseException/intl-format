<?php

namespace Budgegeria\IntlFormat\Tests\Formatter;

use Budgegeria\IntlFormat\Formatter\TimeZoneFormatter;
use DateTime;
use DateTimeZone;
use IntlTimeZone;

class TimeZoneFormatterTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @param string $typeSpecifier
     * @dataProvider provideTypeSpecifier
     */
    public function testHas($typeSpecifier)
    {
        $formatter = new TimeZoneFormatter('en_US');

        $this->assertTrue($formatter->has($typeSpecifier));
    }

    /**
     * @dataProvider provideTimeZones
     */
    public function testFormatValue($expected, $typeSpecifier, $value)
    {
        $formatter = new TimeZoneFormatter('en_US');

        $this->assertSame($expected, $formatter->formatValue($typeSpecifier, $value));
    }

    /**
     * @expectedException \Budgegeria\IntlFormat\Exception\InvalidValueException
     * @dataProvider provideTypeSpecifier
     */
    public function testFormatValueInvalidValue($typeSpecifier)
    {
        $formatter = new TimeZoneFormatter('en_US');

        $formatter->formatValue($typeSpecifier, 'foo');
    }

    /**
     * @return array
     */
    public function provideTypeSpecifier()
    {
        return [
            'timeseries_id' => ['timeseries_id'],
            'timeseries_name' => ['timeseries_name'],
            'timeseries_short' => ['timeseries_short'],
        ];
    }

    /**
     * @return array
     */
    public function provideTimeZones()
    {
        $datetime = $this->createDateTime('2016-05-01', 'US/Arizona');
        $timezone = $datetime->getTimezone();
        $intlTimezone = IntlTimeZone::fromDateTimeZone($timezone);

        return [
            'timeseries_id_ts' => ['US/Arizona', 'timeseries_id', $timezone],
            'timeseries_id_dt' => ['US/Arizona', 'timeseries_id', $datetime],
            'timeseries_id_its' => ['US/Arizona', 'timeseries_id', $intlTimezone],

            'timeseries_name_ts' => ['Mountain Standard Time', 'timeseries_name', $timezone],
            'timeseries_name_dt' => ['Mountain Standard Time', 'timeseries_name', $datetime],
            'timeseries_name_its' => ['Mountain Standard Time', 'timeseries_name', $intlTimezone],

            'timeseries_short_ts' => ['MST', 'timeseries_short', $timezone],
            'timeseries_short_dt' => ['MST', 'timeseries_short', $datetime],
            'timeseries_short_its' => ['MST', 'timeseries_short', $intlTimezone],
        ];
    }

    /**
     * @param string $date
     * @param string $timezone
     * @return DateTime
     */
    private function createDateTime($date, $timezone)
    {
        return new DateTime((string) $date, new DateTimeZone((string) $timezone));
    }
}
