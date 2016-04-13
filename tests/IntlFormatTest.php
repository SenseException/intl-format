<?php

namespace Budgegeria\IntlFormat\Tests;

use Budgegeria\IntlFormat\Formatter\FormatterInterface;
use Budgegeria\IntlFormat\IntlFormat;

class IntlFormatTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Basic format test
     */
    public function testFormat()
    {
        $message = 'Hello "%world", how are you';

        $formatter = $this->createMock(FormatterInterface::class);
        $formatter->expects($this->once())
            ->method('has')
            ->with('world')
            ->willReturn(true);
        $formatter->expects($this->once())
            ->method('formatValue')
            ->with('world', 'island')
            ->willReturn('island');

        $intlFormat = new IntlFormat([$formatter]);

        $this->assertSame('Hello "island", how are you', $intlFormat->format($message, 'island'));
    }

    /**
     * A test for %-escaped messages.
     *
     * @dataProvider provideEscapedMessages
     */
    public function testEscapedFormat($message, $expected)
    {
        $formatter = $this->createMock(FormatterInterface::class);
        $formatter->expects($this->once())
            ->method('has')
            ->with('world')
            ->willReturn(true);
        $formatter->expects($this->once())
            ->method('formatValue')
            ->with('world', 'island')
            ->willReturn('island');

        $intlFormat = new IntlFormat([$formatter]);

        $this->assertSame($expected, $intlFormat->format($message, 'island'));
    }

    /**
     * A test for argument swapping.
     */
    public function testArgumentSwappingFormat()
    {
        $message = '%swap %swap %1$swap';

        $formatter = $this->createMock(FormatterInterface::class);
        $formatter->expects($this->atLeastOnce())
            ->method('has')
            ->with('swap')
            ->willReturn(true);
        $formatter->expects($this->atLeastOnce())
            ->method('formatValue')
            ->with('swap', \PHPUnit_Framework_Assert::anything())
            ->willReturnOnConsecutiveCalls('value1', 'value2', 'value1');

        $intlFormat = new IntlFormat([$formatter]);

        $expected = 'value1 value2 value1';
        $this->assertSame($expected, $intlFormat->format($message, 'value1', 'value2'));
    }

    /**
     * A test for argument swapping.
     */
    public function testArgumentSwappingOrder()
    {
        $message = '%3$swap %2$swap %1$swap';

        $formatter = $this->createMock(FormatterInterface::class);
        $formatter->expects($this->atLeastOnce())
            ->method('has')
            ->with('swap')
            ->willReturn(true);
        $formatter->expects($this->atLeastOnce())
            ->method('formatValue')
            ->with('swap', \PHPUnit_Framework_Assert::anything())
            ->willReturnOnConsecutiveCalls('value3', 'value2', 'value1');

        $intlFormat = new IntlFormat([$formatter]);

        $expected = 'value3 value2 value1';
        $this->assertSame($expected, $intlFormat->format($message, 'value1', 'value2', 'value3'));
    }

    /**
     * %date is an unknown type specifier in this test.
     */
    public function testMissingTypeSpecifier()
    {
        $message = 'Hello %world, Today is %date';

        $formatter = $this->createMock(FormatterInterface::class);
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
     * More type specifier than values.
     *
     * @expectedException \Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException
     */
    public function testInvalidValueTypeSpecifierCount()
    {
        $message = 'Hello %world, Today is %date';

        $intlFormat = new IntlFormat([]);
        $intlFormat->format($message, 'island');
    }

    /**
     * Less type specifier than values.
     *
     * @expectedException \Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException
     */
    public function testEscapedInvalidTypeSpecifierCount()
    {
        $message = 'Hello %%world';

        $intlFormat = new IntlFormat([]);
        $intlFormat->format($message, 'island');
    }

    /**
     * There aren't enough values for %5$world.
     *
     * @expectedException \Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException
     */
    public function testWrongTypeSpecifierIndex()
    {
        $message = 'Hello %5$world, Today is %date';

        $formatter = $this->createMock(FormatterInterface::class);
        $intlFormat = new IntlFormat([$formatter]);

        $intlFormat->format($message, 'island', new \DateTime());
    }

    /**
     * %0$world is an invalid type specifier
     *
     * @expectedException \Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException
     */
    public function testInvalidTypeSpecifier()
    {
        $message = 'Hello %0$world, Today is %date';

        $formatter = $this->createMock(FormatterInterface::class);
        $intlFormat = new IntlFormat([$formatter]);

        $intlFormat->format($message, 'island', new \DateTime());
    }

    /**
     * @return array
     */
    public function provideEscapedMessages()
    {
        return [
            ['Hello %world, how %%are you', 'Hello island, how %are you'],
            ['Hello %world, how %% are you', 'Hello island, how % are you'],
        ];
    }
}
