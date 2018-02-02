<?php

namespace Budgegeria\IntlFormat\Tests\MessageParser;

use Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException;
use Budgegeria\IntlFormat\MessageParser\SprintfParser;
use PHPUnit\Framework\TestCase;

class SprintfParserTest extends TestCase
{
    /**
     * A test for argument swapping.
     */
    public function testArgumentSwappingFormat()
    {
        $message = '%swap %swap %1$swap';

        $parser = new SprintfParser();

        $parsed = $parser->parseMessage($message, ['value1', 'value2']);

        self::assertSame([0 => 'swap', 2 => 'swap', 4 => 'swap'], $parsed->typeSpecifiers, 'wrong type specifier');
        self::assertSame(['value1', 'value2', 'value1'], $parsed->values, 'Wrong values');
        self::assertSame(['%swap', ' ', '%swap', ' ', '%1$swap'], $parsed->parsedMessage, 'Wrong parsed message');
    }

    /**
     * A test for argument swapping.
     */
    public function testArgumentSwappingOrder()
    {
        $message = '%3$swap %2$swap %1$swap';

        $parser = new SprintfParser();

        $parsed = $parser->parseMessage($message, ['value1', 'value2', 'value3']);

        self::assertSame([0 => 'swap', 2 => 'swap', 4 => 'swap'], $parsed->typeSpecifiers, 'wrong type specifier');
        self::assertSame(['value3', 'value2', 'value1'], $parsed->values, 'Wrong values');
        self::assertSame(['%3$swap', ' ', '%2$swap', ' ', '%1$swap'], $parsed->parsedMessage, 'Wrong parsed message');
    }

    /**
     * %0$world is an invalid type specifier
     */
    public function testInvalidTypeSpecifier()
    {
        $this->expectException(InvalidTypeSpecifierException::class);
        $this->expectExceptionCode(30);

        $message = 'Hello %0$world, Today is %date';

        $parser = new SprintfParser();

        $parser->parseMessage($message, ['island', new \DateTime()]);
    }

    /**
     * Basic format test
     */
    public function testParseMessage()
    {
        $message = 'Hello "%world", how are you';

        $parser = new SprintfParser();
        $parsed = $parser->parseMessage($message, ['island']);

        self::assertSame([1 => 'world'], $parsed->typeSpecifiers, 'Wrong type specifier');
        self::assertSame(['island'], $parsed->values, 'Wrong values');
        self::assertSame(['Hello "', '%world', '", how are you'], $parsed->parsedMessage, 'Wrong parsed message');
    }

    /**
     * Basic fraction digit test
     */
    public function testParseMessageWithFractionDigits()
    {
        $message = 'Hello "%.12world", how are you';

        $parser = new SprintfParser();
        $parsed = $parser->parseMessage($message, ['island']);

        self::assertSame([1 => '.12world'], $parsed->typeSpecifiers, 'Wrong type specifier');
        self::assertSame(['island'], $parsed->values, 'Wrong values');
        self::assertSame(['Hello "', '%.12world', '", how are you'], $parsed->parsedMessage, 'Wrong parsed message');
    }

    public function testParseMessageWithInvalidFractionDigits()
    {
        $message = 'Hello "%..12world", how are you';

        $parser = new SprintfParser();
        $parsed = $parser->parseMessage($message, ['island']);

        self::assertSame([], $parsed->typeSpecifiers, 'Wrong type specifier');
        self::assertSame([], $parsed->values, 'Wrong values');
        self::assertSame(['Hello "', '%.', '.12world", how are you'], $parsed->parsedMessage, 'Wrong parsed message');
    }

    public function testParseMessageWithInterchangedFractionDigits()
    {
        $message = 'Hello "%12.world", how are you';

        $parser = new SprintfParser();
        $parsed = $parser->parseMessage($message, ['island']);

        self::assertSame([1 => '12'], $parsed->typeSpecifiers, 'Wrong type specifier');
        self::assertSame(['island'], $parsed->values, 'Wrong values');
        self::assertSame(['Hello "', '%12', '.world", how are you'], $parsed->parsedMessage, 'Wrong parsed message');
    }

    /**
     * A test for argument swapping with fraction digits.
     */
    public function testArgumentSwappingOrderWithFractionDigits()
    {
        $message = '%3$.1swap %2$.4swap %1$.3swap';

        $parser = new SprintfParser();

        $parsed = $parser->parseMessage($message, ['value1', 'value2', 'value3']);

        self::assertSame([0 => '.1swap', 2 => '.4swap', 4 => '.3swap'], $parsed->typeSpecifiers, 'wrong type specifier');
        self::assertSame(['value3', 'value2', 'value1'], $parsed->values, 'Wrong values');
        self::assertSame(['%3$.1swap', ' ', '%2$.4swap', ' ', '%1$.3swap'], $parsed->parsedMessage, 'Wrong parsed message');
    }

    /**
     * Basic format test
     */
    public function testParseEscapedMessage()
    {
        $message = 'Hello "%world", how are you %%person %%';

        $parser = new SprintfParser();
        $parsed = $parser->parseMessage($message, ['island']);

        self::assertSame([1 => 'world'], $parsed->typeSpecifiers, 'Wrong type specifier');
        self::assertSame(['island'], $parsed->values, 'Wrong values');
        self::assertSame(['Hello "', '%world', '", how are you ', '%person', ' ', '%'], $parsed->parsedMessage, 'Wrong parsed message');
    }

    /**
     * There aren't enough values for %5$world.
     */
    public function testWrongTypeSpecifierIndex()
    {
        $this->expectException(InvalidTypeSpecifierException::class);
        $this->expectExceptionCode(10);

        $message = 'Hello %5$world, Today is %date';

        $parser = new SprintfParser();
        $parser->parseMessage($message, ['island', new \DateTime()]);
    }
}
