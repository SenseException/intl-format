<?php

namespace Budgegeria\IntlFormat\Tests\MessageParser;

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

        $this->assertSame([0 => 'swap', 2 => 'swap', 4 => 'swap'], $parsed->typeSpecifiers, 'wrong type specifier');
        $this->assertSame(['value1', 'value2', 'value1'], $parsed->values, 'Wrong values');
        $this->assertSame(['%swap', ' ', '%swap', ' ', '%1$swap'], $parsed->parsedMessage, 'Wrong parsed message');
    }

    /**
     * A test for argument swapping.
     */
    public function testArgumentSwappingOrder()
    {
        $message = '%3$swap %2$swap %1$swap';

        $parser = new SprintfParser();

        $parsed = $parser->parseMessage($message, ['value1', 'value2', 'value3']);

        $this->assertSame([0 => 'swap', 2 => 'swap', 4 => 'swap'], $parsed->typeSpecifiers, 'wrong type specifier');
        $this->assertSame(['value3', 'value2', 'value1'], $parsed->values, 'Wrong values');
        $this->assertSame(['%3$swap', ' ', '%2$swap', ' ', '%1$swap'], $parsed->parsedMessage, 'Wrong parsed message');
    }

    /**
     * %0$world is an invalid type specifier
     *
     * @expectedException \Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException
     * @expectedExceptionCode 30
     */
    public function testInvalidTypeSpecifier()
    {
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

        $this->assertSame([1 => 'world'], $parsed->typeSpecifiers, 'Wrong type specifier');
        $this->assertSame(['island'], $parsed->values, 'Wrong values');
        $this->assertSame(['Hello "', '%world', '", how are you'], $parsed->parsedMessage, 'Wrong parsed message');
    }

    /**
     * Basic format test
     */
    public function testParseEscapedMessage()
    {
        $message = 'Hello "%world", how are you %%person %%';

        $parser = new SprintfParser();
        $parsed = $parser->parseMessage($message, ['island']);

        $this->assertSame([1 => 'world'], $parsed->typeSpecifiers, 'Wrong type specifier');
        $this->assertSame(['island'], $parsed->values, 'Wrong values');
        $this->assertSame(['Hello "', '%world', '", how are you ', '%person', ' ', '%'], $parsed->parsedMessage, 'Wrong parsed message');
    }

    /**
     * There aren't enough values for %5$world.
     *
     * @expectedException \Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException
     * @expectedExceptionCode 10
     */
    public function testWrongTypeSpecifierIndex()
    {
        $message = 'Hello %5$world, Today is %date';

        $parser = new SprintfParser();
        $parser->parseMessage($message, ['island', new \DateTime()]);
    }
}
