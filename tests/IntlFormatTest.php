<?php

declare(strict_types=1);

namespace Budgegeria\IntlFormat\Tests;

use Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException;
use Budgegeria\IntlFormat\Formatter\FormatterInterface;
use Budgegeria\IntlFormat\IntlFormat;
use Budgegeria\IntlFormat\MessageParser\MessageMetaData;
use Budgegeria\IntlFormat\MessageParser\MessageParserInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class IntlFormatTest extends TestCase
{
    /**
     * Basic format test
     */
    public function testFormat() : void
    {
        $message = 'Hello "{{world}}", how are you';

        /** @var FormatterInterface|MockObject $formatter */
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
            ['island']
        );

        /** @var MessageParserInterface|MockObject $parser */
        $parser = $this->createMock(MessageParserInterface::class);
        $parser->expects(self::once())
            ->method('parseMessage')
            ->with($message, ['island'])
            ->willReturn($parsed);

        $intlFormat = new IntlFormat([$formatter], $parser);

        self::assertSame('Hello "island", how are you', $intlFormat->format($message, 'island'));
    }

    /**
     * %date is an unknown type specifier in this test.
     */
    public function testMissingTypeSpecifier() : void
    {
        $message = 'Hello {{world}}, Today is {{date}}';

        /** @var FormatterInterface|MockObject $formatter */
        $formatter = $this->createMock(FormatterInterface::class);
        $formatter->expects(self::atLeastOnce())
            ->method('has')
            ->willReturnMap([
                ['world', true],
                ['date', false]
            ]);
        $formatter->expects(self::once())
            ->method('formatValue')
            ->with('world', 'island')
            ->willReturn('island');

        $dateTime = new \DateTime();

        $parsed = new MessageMetaData(
            ['Hello ', '{{world}}', ', Today is ', '{{date}}'],
            [1 => 'world', 3 => 'date'],
            ['island', $dateTime]
        );

        /** @var MessageParserInterface|MockObject $parser */
        $parser = $this->createMock(MessageParserInterface::class);
        $parser->expects(self::once())
            ->method('parseMessage')
            ->with($message, ['island', $dateTime])
            ->willReturn($parsed);

        $intlFormat = new IntlFormat([$formatter], $parser);

        self::assertSame('Hello island, Today is {{date}}', $intlFormat->format($message, 'island', $dateTime));
    }

    /**
     * More type specifier than values.
     */
    public function testInvalidValueTypeSpecifierCount() : void
    {
        $this->expectException(InvalidTypeSpecifierException::class);
        $this->expectExceptionCode(40);

        $message = 'Hello {{world}}, Today is {{date}}';

        $parsed = new MessageMetaData(
            ['Hello ', '{{world}}', ', Today is ', '{{date}}'],
            [1 => 'world', 3 => 'date'],
            ['island']
        );

        /** @var MessageParserInterface|MockObject $parser */
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
    public function testEscapedInvalidTypeSpecifierCount() : void
    {
        $this->expectException(InvalidTypeSpecifierException::class);
        $this->expectExceptionCode(40);

        $message = 'Hello %%world';

        $parsed = new MessageMetaData(
            ['Hello ', '%world'],
            [],
            ['island']
        );

        /** @var MessageParserInterface|MockObject $parser */
        $parser = $this->createMock(MessageParserInterface::class);
        $parser->expects(self::once())
            ->method('parseMessage')
            ->with($message, ['island'])
            ->willReturn($parsed);

        $intlFormat = new IntlFormat([], $parser);
        $intlFormat->format($message, 'island');
    }
}
