<?php

namespace Budgegeria\IntlFormat\Tests\Exception;

use Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException;
use PHPUnit\Framework\TestCase;

class InvalidTypeSpecifierExceptionTest extends TestCase
{
    /**
     * @expectedException \Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException
     * @expectedExceptionMessage The type specifier "%test" doesn't match with the given values.
     */
    public function testUnmatchedTypeSpecifier()
    {
        throw InvalidTypeSpecifierException::unmatchedTypeSpecifier('%test');
    }

    /**
     * @expectedException \Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException
     * @expectedExceptionMessage No type specifier are in the message text.
     */
    public function testNoTypeSpecifier()
    {
        throw InvalidTypeSpecifierException::noTypeSpecifier();
    }

    /**
     * @expectedException \Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException
     * @expectedExceptionMessage "%0$test" is not a valid type specifier.
     */
    public function testInvalidTypeSpecifier()
    {
        throw InvalidTypeSpecifierException::invalidTypeSpecifier('%0$test');
    }

    /**
     * @expectedException \Budgegeria\IntlFormat\Exception\InvalidTypeSpecifierException
     * @expectedExceptionMessage Value count of "1" doesn't match type specifier count of "2"
     */
    public function testInvalidTypeSpecifierCount()
    {
        throw InvalidTypeSpecifierException::invalidTypeSpecifierCount(1, 2);
    }
}