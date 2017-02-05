<?php

namespace Budgegeria\IntlFormat\Tests;

use Budgegeria\IntlFormat\Factory;
use Budgegeria\IntlFormat\IntlFormat;
use DateTime;
use DateTimeZone;
use PHPUnit\Framework\TestCase;

class FactoryTest extends TestCase
{
    public function testCreateIntlFormat()
    {
        $intlFormat = (new Factory())->createIntlFormat('de_DE');

        $this->assertInstanceOf(IntlFormat::class, $intlFormat);
    }

    public function testCreateIntlFormatIntegration()
    {
        $intlFormat = (new Factory())->createIntlFormat('en_US');
        $date = new DateTime();
        $date->setDate(2016, 3, 1);
        $date->setTime(5, 30);
        $date->setTimezone(new DateTimeZone('US/Arizona'));

        $this->assertSame('Today is 3/1/16', $intlFormat->format('Today is %date_short', $date));
        $this->assertSame('I got 1,002.25 as average value', $intlFormat->format('I got %number as average value', 1002.25));
        $this->assertSame('I is 5:30 AM on my clock.', $intlFormat->format('I is %time_short on my clock.', $date));
        $this->assertSame('The timezone id is US/Arizona.', $intlFormat->format('The timezone id is %timeseries_id.', $date));
        $this->assertSame('I am from Italy.', $intlFormat->format('I am from %region.', 'it_IT'));
    }
}
