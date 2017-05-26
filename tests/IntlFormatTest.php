<?php

namespace Budgegeria\IntlFormat\Tests;

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

        $parsed = new MessageMetaData();
        $parsed->typeSpecifiers = [1 => 'world'];
        $parsed->values = ['island'];
        $parsed->parsedMessage = ['Hello "', '{{world}}', '", how are you'];

        $parser = $this->createMock(MessageParserInterface::class);
        $parser->expects($this->once())
            ->method('parseMessage')
            ->with($message, ['island'])
            ->willReturn($parsed);

        $intlFormat = new IntlFormat([$formatter], $parser);

        $this->assertSame('Hello "island", how are you', $intlFormat->format($message, 'island'));
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

        $parsed = new MessageMetaData();
        $parsed->typeSpecifiers = [1 => 'world', 3 => 'date'];
        $parsed->values = ['island', $dateTime];
        $parsed->parsedMessage = ['Hello ', '{{world}}', ', Today is ', '{{date}}'];

        $parser = $this->createMock(MessageParserInterface::class);
        $parser->expects($this->once())
            ->method('parseMessage')
            ->with($message, ['island', $dateTime])
            ->willReturn($parsed);

        $intlFormat = new IntlFormat([$formatter], $parser);

        $this->assertSame('Hello island, Today is {{date}}', $intlFormat->format($message, 'island', $dateTime));
    }

    /**
     * More type specifier than values.
     *
     * @expectedException \Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException
     * @expectedExceptionCode 40
     */
    public function testInvalidValueTypeSpecifierCount()
    {
        $message = 'Hello {{world}}, Today is {{date}}';

        $parsed = new MessageMetaData();
        $parsed->typeSpecifiers = [1 => 'world', 3 => 'date'];
        $parsed->values = ['island'];
        $parsed->parsedMessage = ['Hello ', '{{world}}', ', Today is ', '{{date}}'];

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
     *
     * @expectedException \Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException
     * @expectedExceptionCode 40
     */
    public function testEscapedInvalidTypeSpecifierCount()
    {
        $message = 'Hello %%world';

        $parsed = new MessageMetaData();
        $parsed->typeSpecifiers = [];
        $parsed->values = ['island'];
        $parsed->parsedMessage = ['Hello ', '%world'];

        $parser = $this->createMock(MessageParserInterface::class);
        $parser->expects($this->once())
            ->method('parseMessage')
            ->with($message, ['island'])
            ->willReturn($parsed);

        $intlFormat = new IntlFormat([], $parser);
        $intlFormat->format($message, 'island');
    }
}
