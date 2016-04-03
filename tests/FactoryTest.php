<?php

namespace Budgegeria\IntlFormat\Tests;

use Budgegeria\IntlFormat\Factory;
use Budgegeria\IntlFormat\IntlFormat;

class FactoryTest extends \PHPUnit_Framework_TestCase
{
    public function testCreateIntlFormat()
    {
        $intlFormat = (new Factory())->createIntlFormat('de_DE');

        $this->assertInstanceOf(IntlFormat::class, $intlFormat);
    }

    public function testCreateIntlFormatIntegration()
    {
        $intlFormat = (new Factory())->createIntlFormat('en_US');
        $date = new \DateTime();
        $date->setDate(2016, 3, 1);
        $date->setTime(5, 30);

        $this->assertSame('Today is 3/1/16', $intlFormat->format('Today is %date_short', $date));
        $this->assertSame('I got 1,002.25 as average value', $intlFormat->format('I got %number as average value', 1002.25));
        $this->assertSame('I is 5:30 AM on my clock.', $intlFormat->format('I is %time_short on my clock.', $date));
    }
}
