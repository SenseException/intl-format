<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Tests;

use Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException;
use Budgegeria\IntlFormat\Formatter\FormatterInterface;
use Budgegeria\IntlFormat\IntlFormat;
use Budgegeria\IntlFormat\MessageParser\SprintfParser;
use DateTime;
use PHPUnit\Framework\Assert;
use PHPUnit\Framework\TestCase;

class IntlFormatIntegrationTest extends TestCase
{
    /**
     * Basic format test
     */
    public function testFormat(): void
    {
        $message = 'Hello "%world", how are you';

        $formatter = $this->createMock(FormatterInterface::class);
        $formatter->expects(self::once())
            ->method('has')
            ->with('world')
            ->willReturn(true);
        $formatter->expects(self::once())
            ->method('formatValue')
            ->with('world', 'island')
            ->willReturn('island');

        $intlFormat = new IntlFormat([$formatter], new SprintfParser());

        self::assertSame('Hello "island", how are you', $intlFormat->format($message, 'island'));
    }

    /**
     * A test for %-escaped messages.
     *
     * @dataProvider provideEscapedMessages
     */
    public function testEscapedFormat(string $message, string $expected): void
    {
        $formatter = $this->createMock(FormatterInterface::class);
        $formatter->expects(self::once())
            ->method('has')
            ->with('world')
            ->willReturn(true);
        $formatter->expects(self::once())
            ->method('formatValue')
            ->with('world', 'island')
            ->willReturn('island');

        $intlFormat = new IntlFormat([$formatter], new SprintfParser());

        self::assertSame($expected, $intlFormat->format($message, 'island'));
    }

    /**
     * A test for argument swapping.
     */
    public function testArgumentSwappingFormat(): void
    {
        $message = '%swap %swap %1$swap';

        $formatter = $this->createMock(FormatterInterface::class);
        $formatter->expects(self::atLeastOnce())
            ->method('has')
            ->with('swap')
            ->willReturn(true);
        $formatter->expects(self::atLeastOnce())
            ->method('formatValue')
            ->with('swap', Assert::anything())
            ->willReturnArgument(1);

        $intlFormat = new IntlFormat([$formatter], new SprintfParser());

        $expected = 'value1 value2 value1';
        self::assertSame($expected, $intlFormat->format($message, 'value1', 'value2'));
    }

    /**
     * A test for argument swapping.
     */
    public function testArgumentSwappingOrder(): void
    {
        $message = '%3$swap %2$swap %1$swap';

        $formatter = $this->createMock(FormatterInterface::class);
        $formatter->expects(self::atLeastOnce())
            ->method('has')
            ->with('swap')
            ->willReturn(true);
        $formatter->expects(self::atLeastOnce())
            ->method('formatValue')
            ->with('swap', Assert::anything())
            ->willReturnArgument(1);

        $intlFormat = new IntlFormat([$formatter], new SprintfParser());

        $expected = 'value3 value2 value1';
        self::assertSame($expected, $intlFormat->format($message, 'value1', 'value2', 'value3'));
    }

    /**
     * %date is an unknown type specifier in this test.
     */
    public function testMissingTypeSpecifier(): void
    {
        $message = 'Hello %world, Today is %date';

        $formatter = $this->createMock(FormatterInterface::class);
        $formatter->expects(self::atLeastOnce())
            ->method('has')
            ->willReturnMap([
                ['world', true],
                ['date', false],
            ]);
        $formatter->expects(self::once())
            ->method('formatValue')
            ->with('world', 'island')
            ->willReturn('island');

        $intlFormat = new IntlFormat([$formatter], new SprintfParser());

        self::assertSame('Hello island, Today is %date', $intlFormat->format($message, 'island', new DateTime()));
    }

    /**
     * More type specifier than values.
     */
    public function testInvalidValueTypeSpecifierCount(): void
    {
        $this->expectException(InvalidTypeSpecifierException::class);
        $this->expectExceptionCode(10);

        $message = 'Hello %world, Today is %date';

        $intlFormat = new IntlFormat([], new SprintfParser());
        $intlFormat->format($message, 'island');
    }

    /**
     * Less type specifier than values.
     */
    public function testEscapedInvalidTypeSpecifierCount(): void
    {
        $this->expectException(InvalidTypeSpecifierException::class);
        $this->expectExceptionCode(20);

        $message = 'Hello %%world';

        $intlFormat = new IntlFormat([], new SprintfParser());
        $intlFormat->format($message, 'island');
    }

    /**
     * There aren't enough values for %5$world.
     */
    public function testWrongTypeSpecifierIndex(): void
    {
        $this->expectException(InvalidTypeSpecifierException::class);
        $this->expectExceptionCode(10);

        $message = 'Hello %5$world, Today is %date';

        $formatter  = $this->createMock(FormatterInterface::class);
        $intlFormat = new IntlFormat([$formatter], new SprintfParser());

        $intlFormat->format($message, 'island', new DateTime());
    }

    /**
     * %0$world is an invalid type specifier
     */
    public function testInvalidTypeSpecifier(): void
    {
        $this->expectException(InvalidTypeSpecifierException::class);
        $this->expectExceptionCode(30);

        $message = 'Hello %0$world, Today is %date';

        $formatter  = $this->createMock(FormatterInterface::class);
        $intlFormat = new IntlFormat([$formatter], new SprintfParser());

        $intlFormat->format($message, 'island', new DateTime());
    }

    public function testAddFormatOverride(): void
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

        $intlFormat = new IntlFormat([], new SprintfParser());
        $intlFormat->addFormatter($formatter1);
        $intlFormat->addFormatter($formatter2);

        self::assertSame('Hello "city", how are you', $intlFormat->format($message, 'city'));
    }

    /** @return string[][] */
    public static function provideEscapedMessages(): array
    {
        return [
            ['Hello %world, how %%are you', 'Hello island, how %are you'],
            ['Hello %world, how %% are you', 'Hello island, how % are you'],
        ];
    }
}
