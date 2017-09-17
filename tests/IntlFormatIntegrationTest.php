<?php

namespace Budgegeria\IntlFormat\Tests;

use Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException;
use Budgegeria\IntlFormat\Formatter\FormatterInterface;
use Budgegeria\IntlFormat\IntlFormat;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class IntlFormatIntegrationTest extends TestCase
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

        self::assertSame('Hello "island", how are you', $intlFormat->format($message, 'island'));
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

        self::assertSame($expected, $intlFormat->format($message, 'island'));
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
            ->with('swap', Assert::anything())
            ->willReturnOnConsecutiveCalls('value1', 'value2', 'value1');

        $intlFormat = new IntlFormat([$formatter]);

        $expected = 'value1 value2 value1';
        self::assertSame($expected, $intlFormat->format($message, 'value1', 'value2'));
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
            ->with('swap', Assert::anything())
            ->willReturnOnConsecutiveCalls('value3', 'value2', 'value1');

        $intlFormat = new IntlFormat([$formatter]);

        $expected = 'value3 value2 value1';
        self::assertSame($expected, $intlFormat->format($message, 'value1', 'value2', 'value3'));
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

        self::assertSame('Hello island, Today is %date', $intlFormat->format($message, 'island', new \DateTime()));
    }

    /**
     * More type specifier than values.
     */
    public function testInvalidValueTypeSpecifierCount()
    {
        self::expectException(InvalidTypeSpecifierException::class);
        self::expectExceptionCode(10);

        $message = 'Hello %world, Today is %date';

        $intlFormat = new IntlFormat([]);
        $intlFormat->format($message, 'island');
    }

    /**
     * Less type specifier than values.
     */
    public function testEscapedInvalidTypeSpecifierCount()
    {
        self::expectException(InvalidTypeSpecifierException::class);
        self::expectExceptionCode(20);

        $message = 'Hello %%world';

        $intlFormat = new IntlFormat([]);
        $intlFormat->format($message, 'island');
    }

    /**
     * There aren't enough values for %5$world.
     */
    public function testWrongTypeSpecifierIndex()
    {
        self::expectException(InvalidTypeSpecifierException::class);
        self::expectExceptionCode(10);

        $message = 'Hello %5$world, Today is %date';

        $formatter = $this->createMock(FormatterInterface::class);
        $intlFormat = new IntlFormat([$formatter]);

        $intlFormat->format($message, 'island', new \DateTime());
    }

    /**
     * %0$world is an invalid type specifier
     */
    public function testInvalidTypeSpecifier()
    {
        self::expectException(InvalidTypeSpecifierException::class);
        self::expectExceptionCode(30);

        $message = 'Hello %0$world, Today is %date';

        $formatter = $this->createMock(FormatterInterface::class);
        $intlFormat = new IntlFormat([$formatter]);

        $intlFormat->format($message, 'island', new \DateTime());
    }

    public function testAddFormatOverride()
    {
        $message = 'Hello "%world", how are you';

        $formatter1 = $this->createMock(FormatterInterface::class);
        $formatter1->method('has')
            ->willReturn(true);
        $formatter1->method('formatValue')
            ->willReturn('island');
        $formatter2 = $this->createMock(FormatterInterface::class);
        $formatter2->method('has')
            ->willReturn(true);
        $formatter2->method('formatValue')
            ->willReturn('city');

        $intlFormat = new IntlFormat([]);
        $intlFormat->addFormatter($formatter1);
        $intlFormat->addFormatter($formatter2);

        self::assertSame('Hello "city", how are you', $intlFormat->format($message, 'city'));
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
