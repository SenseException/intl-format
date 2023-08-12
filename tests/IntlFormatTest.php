<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Tests;

use ArrayIterator;
use Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException;
use Budgegeria\IntlFormat\Formatter\FormatterInterface;
use Budgegeria\IntlFormat\IntlFormat;
use Budgegeria\IntlFormat\MessageParser\MessageMetaData;
use Budgegeria\IntlFormat\MessageParser\MessageParserInterface;
use DateTime;
use PHPUnit\Framework\TestCase;

class IntlFormatTest extends TestCase
{
    /**
     * Basic format test
     */
    public function testFormat(): void
    {
        $message = 'Hello "{{world}}", how are you';

        $formatter = $this->createMock(FormatterInterface::class);
        $formatter->expects(self::once())
            ->method('has')
            ->with('world')
            ->willReturn(true);
        $formatter->expects(self::once())
            ->method('formatValue')
            ->with('world', 'island')
            ->willReturn('island');

        $parsed = new MessageMetaData(
            ['Hello "', '{{world}}', '", how are you'],
            [1 => 'world'],
            ['island'],
        );

        $parser = $this->createMock(MessageParserInterface::class);
        $parser->expects(self::once())
            ->method('parseMessage')
            ->with($message, ['island'])
            ->willReturn($parsed);

        $intlFormat = new IntlFormat([$formatter], $parser);

        self::assertSame('Hello "island", how are you', $intlFormat->format($message, 'island'));
    }

    /**
     * Basic format test
     */
    public function testFormatWithIterator(): void
    {
        $message = 'Hello "{{world}}", how are you';

        $formatter = $this->createMock(FormatterInterface::class);
        $formatter->expects(self::once())
            ->method('has')
            ->with('world')
            ->willReturn(true);
        $formatter->expects(self::once())
            ->method('formatValue')
            ->with('world', 'island')
            ->willReturn('island');

        $parsed = new MessageMetaData(
            ['Hello "', '{{world}}', '", how are you'],
            [1 => 'world'],
            ['island'],
        );

        $parser = $this->createMock(MessageParserInterface::class);
        $parser->expects(self::once())
            ->method('parseMessage')
            ->with($message, ['island'])
            ->willReturn($parsed);

        $intlFormat = new IntlFormat(new ArrayIterator([$formatter]), $parser);

        self::assertSame('Hello "island", how are you', $intlFormat->format($message, 'island'));
    }

    /**
     * %date is an unknown type specifier in this test.
     */
    public function testMissingTypeSpecifier(): void
    {
        $message = 'Today is {{date}}. Hello {{world}}';

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

        $dateTime = new DateTime();

        $parsed = new MessageMetaData(
            ['Today is ', '{{date}}.', ' Hello ', '{{world}}'],
            [1 => 'date', 3 => 'world'],
            [$dateTime, 'island'],
        );

        $parser = $this->createMock(MessageParserInterface::class);
        $parser->expects(self::once())
            ->method('parseMessage')
            ->with($message, [$dateTime, 'island'])
            ->willReturn($parsed);

        $intlFormat = new IntlFormat([$formatter], $parser);

        self::assertSame('Today is {{date}}. Hello island', $intlFormat->format($message, $dateTime, 'island'));
    }

    /**
     * More type specifier than values.
     */
    public function testInvalidValueTypeSpecifierCount(): void
    {
        $this->expectException(InvalidTypeSpecifierException::class);
        $this->expectExceptionCode(40);

        $message = 'Hello {{world}}, Today is {{date}}';

        $parsed = new MessageMetaData(
            ['Hello ', '{{world}}', ', Today is ', '{{date}}'],
            [1 => 'world', 3 => 'date'],
            ['island'],
        );

        $parser = $this->createMock(MessageParserInterface::class);
        $parser->expects(self::once())
            ->method('parseMessage')
            ->with($message, ['island'])
            ->willReturn($parsed);

        $intlFormat = new IntlFormat([], $parser);
        $intlFormat->format($message, 'island');
    }

    /**
     * Less type specifier than values.
     */
    public function testEscapedInvalidTypeSpecifierCount(): void
    {
        $this->expectException(InvalidTypeSpecifierException::class);
        $this->expectExceptionCode(40);

        $message = 'Hello %%world';

        $parsed = new MessageMetaData(
            ['Hello ', '%world'],
            [],
            ['island'],
        );

        $parser = $this->createMock(MessageParserInterface::class);
        $parser->expects(self::once())
            ->method('parseMessage')
            ->with($message, ['island'])
            ->willReturn($parsed);

        $intlFormat = new IntlFormat([], $parser);
        $intlFormat->format($message, 'island');
    }
}
