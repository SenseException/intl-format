<?php

namespace Budgegeria\IntlFormat\Tests\Exception;

use Budgegeria\IntlFormat\Exception\IntlFormatException;
use Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException;
use PHPUnit\Framework\TestCase;

class InvalidTypeSpecifierExceptionTest extends TestCase
{
    public function testUnmatchedTypeSpecifier()
    {
        self::expectException(InvalidTypeSpecifierException::class);
        self::expectExceptionCode(10);
        self::expectExceptionMessage('The type specifier "%test" doesn\'t match with the given values.');

        throw InvalidTypeSpecifierException::unmatchedTypeSpecifier('%test');
    }

    public function testNoTypeSpecifier()
    {
        self::expectException(InvalidTypeSpecifierException::class);
        self::expectExceptionCode(20);
        self::expectExceptionMessage('No type specifier are in the message text.');

        throw InvalidTypeSpecifierException::noTypeSpecifier();
    }

    public function testInvalidTypeSpecifier()
    {
        self::expectException(InvalidTypeSpecifierException::class);
        self::expectExceptionCode(30);
        self::expectExceptionMessage('"%0$test" is not a valid type specifier.');

        throw InvalidTypeSpecifierException::invalidTypeSpecifier('%0$test');
    }

    public function testInvalidTypeSpecifierCount()
    {
        self::expectException(InvalidTypeSpecifierException::class);
        self::expectExceptionCode(40);
        self::expectExceptionMessage('Value count of "1" doesn\'t match type specifier count of "2"');

        throw InvalidTypeSpecifierException::invalidTypeSpecifierCount(1, 2);
    }

    public function testParentClass()
    {
        $this->assertInstanceOf(IntlFormatException::class, new InvalidTypeSpecifierException());
    }
}