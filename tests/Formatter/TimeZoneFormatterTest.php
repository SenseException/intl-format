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
        $timezone = new DateTimeZone('Europe/Berlin');
        $datetime = new DateTime();
        $datetime->setTimezone($timezone);
        $intlTimezone = IntlTimeZone::fromDateTimeZone($timezone);

        return [
            'timeseries_id_ts' => ['Europe/Berlin', 'timeseries_id', $timezone],
            'timeseries_id_dt' => ['Europe/Berlin', 'timeseries_id', $datetime],
            'timeseries_id_its' => ['Europe/Berlin', 'timeseries_id', $intlTimezone],

            'timeseries_name_ts' => ['Central European Standard Time', 'timeseries_name', $timezone],
            'timeseries_name_dt' => ['Central European Standard Time', 'timeseries_name', $datetime],
            'timeseries_name_its' => ['Central European Standard Time', 'timeseries_name', $intlTimezone],

            'timeseries_gmt_diff_ts' => ['GMT+1', 'timeseries_gmt_diff', $timezone],
            'timeseries_gmt_diff_dt' => ['GMT+1', 'timeseries_gmt_diff', $datetime],
            'timeseries_gmt_diff_its' => ['GMT+1', 'timeseries_gmt_diff', $intlTimezone],
        ];
    }
}
