<?php

namespace Budgegeria\IntlFormat\Tests;

use Budgegeria\IntlFormat\Formatter\FormatterInterface;
use Budgegeria\IntlFormat\IntlFormat;

class IntlFormatTest extends \PHPUnit_Framework_TestCase
{
    public function testFormatUnknown()
    {
        $message1 = 'Hello   %world';
        $message2 = '%s';
        $message3 = 'count: %%d';

        $intlFormat = new IntlFormat([]);

        $this->assertSame($message1, $intlFormat->format($message1, 0));
        $this->assertSame($message2, $intlFormat->format($message2, 0));
        $this->assertSame($message3, $intlFormat->format($message3, 0));
    }

    public function testFormat()
    {
        $message = 'Hello %world, how are you';

        $formatter = $this->getMock(FormatterInterface::class);
        $formatter->expects($this->once())
            ->method('has')
            ->with('world')
            ->willReturn(true);
        $formatter->expects($this->once())
            ->method('formatValue')
            ->with('world', 'island')
            ->willReturn('island');

        $intlFormat = new IntlFormat([$formatter]);

        $this->assertSame('Hello island, how are you', $intlFormat->format($message, 'island'));
    }

    public function testMissingTypeSpecifier()
    {
        $message = 'Hello %world, Today is %date';

        $formatter = $this->getMock(FormatterInterface::class);
        $formatter->expects($this->atLeastOnce())
            ->method('has')
            ->willReturnMap([
                ['world', true],
                ['date', false]
            ]);
        $formatter->expects($this->once())
            ->method('formatValue')
            ->with('world', 'island')
            ->willReturn('island');

        $intlFormat = new IntlFormat([$formatter]);

        $this->assertSame('Hello island, Today is %date', $intlFormat->format($message, 'island', new \DateTime()));
    }

    /**
     * @expectedException \RuntimeException
     */
    public function testInvalidValueTypeSpecifierCount()
    {
        $message = 'Hello %world, Today is %date';

        $intlFormat = new IntlFormat([]);
        $intlFormat->format($message, 'island');
    }
}
