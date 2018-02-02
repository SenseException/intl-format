<?php

namespace Budgegeria\IntlFormat\Tests;

use Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException;
use Budgegeria\IntlFormat\Formatter\FormatterInterface;
use Budgegeria\IntlFormat\IntlFormat;
use Budgegeria\IntlFormat\MessageParser\MessageMetaData;
use Budgegeria\IntlFormat\MessageParser\MessageParserInterface;
use PHPUnit\Framework\TestCase;

class IntlFormatTest extends TestCase
{
    /**
     * Basic format test
     */
    public function testFormat()
    {
        $message = 'Hello "{{world}}", how are you';

        $formatter = $this->createMock(FormatterInterface::class);
        $formatter->expects($this->once())
            ->method('has')
            ->with('world')
            ->willReturn(true);
        $formatter->expects($this->once())
            ->method('formatValue')
            ->with('world', 'island')
            ->willReturn('island');

        $parsed = new MessageMetaData(
            ['Hello "', '{{world}}', '", how are you'],
            [1 => 'world'],
            ['island']
        );

        $parser = $this->createMock(MessageParserInterface::class);
        $parser->expects($this->once())
            ->method('parseMessage')
            ->with($message, ['island'])
            ->willReturn($parsed);

        $intlFormat = new IntlFormat([$formatter], $parser);

        self::assertSame('Hello "island", how are you', $intlFormat->format($message, 'island'));
    }

    /**
     * %date is an unknown type specifier in this test.
     */
    public function testMissingTypeSpecifier()
    {
        $message = 'Hello {{world}}, Today is {{date}}';

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

        $dateTime = new \DateTime();

        $parsed = new MessageMetaData(
            ['Hello ', '{{world}}', ', Today is ', '{{date}}'],
            [1 => 'world', 3 => 'date'],
            ['island', $dateTime]
        );

        $parser = $this->createMock(MessageParserInterface::class);
        $parser->expects($this->once())
            ->method('parseMessage')
            ->with($message, ['island', $dateTime])
            ->willReturn($parsed);

        $intlFormat = new IntlFormat([$formatter], $parser);

        self::assertSame('Hello island, Today is {{date}}', $intlFormat->format($message, 'island', $dateTime));
    }

    /**
     * More type specifier than values.
     */
    public function testInvalidValueTypeSpecifierCount()
    {
        $this->expectException(InvalidTypeSpecifierException::class);
        $this->expectExceptionCode(40);

        $message = 'Hello {{world}}, Today is {{date}}';

        $parsed = new MessageMetaData(
            ['Hello ', '{{world}}', ', Today is ', '{{date}}'],
            [1 => 'world', 3 => 'date'],
            ['island']
        );

        $parser = $this->createMock(MessageParserInterface::class);
        $parser->expects($this->once())
            ->method('parseMessage')
            ->with($message, ['island'])
            ->willReturn($parsed);

        $intlFormat = new IntlFormat([], $parser);
        $intlFormat->format($message, 'island');
    }

    /**
     * Less type specifier than values.
     */
    public function testEscapedInvalidTypeSpecifierCount()
    {
        $this->expectException(InvalidTypeSpecifierException::class);
        $this->expectExceptionCode(40);

        $message = 'Hello %%world';

        $parsed = new MessageMetaData(
            ['Hello ', '%world'],
            [],
            ['island']
        );

        $parser = $this->createMock(MessageParserInterface::class);
        $parser->expects($this->once())
            ->method('parseMessage')
            ->with($message, ['island'])
            ->willReturn($parsed);

        $intlFormat = new IntlFormat([], $parser);
        $intlFormat->format($message, 'island');
    }
}
