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

    public function testFormatValueNumber()
    {
        $messageFormatter = new MessageFormatter('de_DE');

        $this->assertSame('1.000,1', $messageFormatter->formatValue('number', 1000.1));
        $this->assertSame('1.000', $messageFormatter->formatValue('integer', 1000.1));
        $this->assertSame('100 %', $messageFormatter->formatValue('percent', 1));
        $this->assertSame('1.000,10 €', $messageFormatter->formatValue('currency', 1000.1));
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
}
