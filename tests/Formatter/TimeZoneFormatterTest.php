<?php
/**
 * Created by PhpStorm.
 * User: claudio
 * Date: 03.04.16
 * Time: 22:51
 */

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
            'timeseries_gmt_diff' => ['timeseries_gmt_diff'],
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

            'timeseries_gmt_diff_ts' => ['MST', 'timeseries_gmt_diff', $timezone],
            'timeseries_gmt_diff_dt' => ['MST', 'timeseries_gmt_diff', $datetime],
            'timeseries_gmt_diff_its' => ['MST', 'timeseries_gmt_diff', $intlTimezone],

            'timeseries_name_dt_dl' => ['Central European Summer Time', 'timeseries_name', $this->createDateTime('2016-05-01', 'Europe/Berlin')],
            'timeseries_name_dt_nodl' => ['Central European Standard Time', 'timeseries_name', $this->createDateTime('2016-01-01', 'Europe/Berlin')],
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
