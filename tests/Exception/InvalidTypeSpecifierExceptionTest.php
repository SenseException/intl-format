<?php

namespace Budgegeria\IntlFormat\Tests\Exception;

use Budgegeria\IntlFormat\Exception\IntlFormatException;
use Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException;
use PHPUnit\Framework\TestCase;

class InvalidTypeSpecifierExceptionTest extends TestCase
{
    public function testUnmatchedTypeSpecifier()
    {
        $this->expectException(InvalidTypeSpecifierException::class);
        $this->expectExceptionCode(10);
        $this->expectExceptionMessage('The type specifier "%test" doesn\'t match with the given values.');

        throw InvalidTypeSpecifierException::unmatchedTypeSpecifier('%test');
    }

    public function testNoTypeSpecifier()
    {
        $this->expectException(InvalidTypeSpecifierException::class);
        $this->expectExceptionCode(20);
        $this->expectExceptionMessage('No type specifier are in the message text.');

        throw InvalidTypeSpecifierException::noTypeSpecifier();
    }

    public function testInvalidTypeSpecifier()
    {
        $this->expectException(InvalidTypeSpecifierException::class);
        $this->expectExceptionCode(30);
        $this->expectExceptionMessage('"%0$test" is not a valid type specifier.');

        throw InvalidTypeSpecifierException::invalidTypeSpecifier('%0$test');
    }

    public function testInvalidTypeSpecifierCount()
    {
        $this->expectException(InvalidTypeSpecifierException::class);
        $this->expectExceptionCode(40);
        $this->expectExceptionMessage('Value count of "1" doesn\'t match type specifier count of "2"');

        throw InvalidTypeSpecifierException::invalidTypeSpecifierCount(1, 2);
    }

    public function testParentClass()
    {
        self::assertInstanceOf(IntlFormatException::class, new InvalidTypeSpecifierException());
    }
}